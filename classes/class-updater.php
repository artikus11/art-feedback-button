<?php

namespace ART\AFB;

/**
 * Class Updater file
 *
 * Класс автоматических обновлений с GitHub
 *
 * @see     https://github.com/rayman813/smashing-updater-plugin
 * @see     https://www.smashingmagazine.com/2015/08/deploy-wordpress-plugins-with-github-using-transients/
 * @see     https://github.com/MilesS/smashing-updater-plugin
 *
 */
class Updater {

	/**
	 * @var string
	 */
	private string $file;

	/**
	 * @var array|null
	 */
	private ?array $plugin = null;

	/**
	 * @var string|null
	 */
	private ?string $basename = null;

	/**
	 * @var bool
	 */
	private ?bool $active = false;

	/**
	 * @var string
	 */
	private string $username;

	/**
	 * @var string
	 */
	private string $repository;

	/**
	 * @var string
	 */
	private string $authorize_token;

	/**
	 * @var array|null
	 */
	private ?array $github_response = null;


	public function __construct( $file ) {

		$this->file = $file;

		add_action( 'admin_init', [ $this, 'set_plugin_properties' ] );

	}


	/**
	 *
	 */
	public function set_plugin_properties(): void {

		$this->plugin   = get_plugin_data( $this->file );
		$this->basename = plugin_basename( $this->file );
		$this->active   = is_plugin_active( $this->basename );
	}


	/**
	 * @param $username
	 */
	public function set_username( $username ): void {

		$this->username = $username;
	}


	/**
	 * @param $repository
	 */
	public function set_repository( $repository ): void {

		$this->repository = $repository;
	}


	/**
	 * @param  string $token
	 */
	public function set_authorize( string $token ): void {

		$this->authorize_token = $token;
	}


	/**
	 *
	 */
	private function get_repository_data(): void {

		$args = [];

		if ( is_null( $this->github_response ) ) {

			$request_uri = sprintf( 'https://api.github.com/repos/%s/%s/releases/latest', $this->username, $this->repository );

			if ( $this->authorize_token ) {
				$args ['headers'] = [
					'Authorization' => 'Bearer ' . base64_decode( $this->authorize_token ),
				];
			}

			$request = wp_remote_get( $request_uri, $args );

			$response = [];

			if ( ! is_wp_error( $request ) ) {
				try {
					$response = json_decode( wp_remote_retrieve_body( $request ), true, 512, JSON_THROW_ON_ERROR );
				} catch ( \JsonException $e ) {
				}
			}

			if ( is_array( $response ) ) {
				$assets = current( $response['assets'] );

				$this->github_response = [
					'tag_name'      => $response['tag_name'],
					'downloaded'    => $assets['download_count'],
					'updates'       => $response['body'],
					'last_updated'  => $response['published_at'],
					'download_link' => $assets['browser_download_url'],
				];
			}

		}
	}


	/**
	 *
	 */
	public function init(): void {

		add_filter( 'pre_set_site_transient_update_plugins', [ $this, 'modify_transient' ], 10, 1 );
		add_filter( 'plugins_api', [ $this, 'plugin_popup' ], 10, 3 );
		add_filter( 'upgrader_post_install', [ $this, 'after_install' ], 10, 3 );

		add_filter(
			'upgrader_pre_download',
			function () {

				add_filter( 'http_request_args', [ $this, 'download_package' ], 15, 2 );

				return false;
			}
		);
	}


	/**
	 *
	 */
	public function modify_transient( $transient ) {

		if ( empty( property_exists( $transient, 'checked' ) ) ) {
			return $transient;
		}

		$checked = $transient->checked;

		if ( ! isset($checked[ $this->basename ]) ) {
			return $transient;
		}

		$this->get_repository_data();

		if ( empty( $this->github_response ) ) {
			return $transient;
		}

		$out_of_date = version_compare( $this->github_response['tag_name'], $checked[ $this->basename ], 'gt' );

		if ( $out_of_date ) {

			$new_files = $this->github_response['download_link'];

			$slug = current( explode( '/', $this->basename ) );

			$plugin = [
				'url'         => $this->plugin["PluginURI"],
				'slug'        => $slug,
				'package'     => $new_files,
				'new_version' => $this->github_response['tag_name'],
			];

			$transient->response[ $this->basename ] = (object) $plugin;
		}

		return $transient;
	}


	/**
	 *
	 */
	public function plugin_popup( $result, $action, $args ) {

		if ( ! empty( $args->slug ) && $args->slug === current( explode( '/', $this->basename ) ) ) {

			$this->get_repository_data();

			$plugin = [
				'name'              => $this->plugin["Name"],
				'slug'              => $this->basename,
				'requires'          => '5.5',
				'tested'            => '6.1',
				'rating'            => '100.0',
				'num_ratings'       => '1',
				'downloaded'        => $this->github_response['downloaded'],
				'added'             => $this->github_response['last_updated'],
				'version'           => $this->github_response['tag_name'],
				'author'            => $this->plugin["AuthorName"],
				'author_profile'    => $this->plugin["AuthorURI"],
				'last_updated'      => $this->github_response['last_updated'],
				'homepage'          => $this->plugin["PluginURI"],
				'short_description' => $this->plugin["Description"],
				'sections'          => [
					'Description' => $this->plugin["Description"],
					'Updates'     => $this->github_response['updates'],
				],
				'download_link'     => $this->github_response['download_link'],
			];

			return (object) $plugin;
		}

		return $result;
	}


	public function download_package( $args, $url ) {

		if ( ( null !== $args['filename'] ) && $this->authorize_token ) {
			$args = array_merge(
				$args,
				[
					"headers" => [
						"Authorization" => "token {$this->authorize_token}",
					],
				]
			);
		}

		remove_filter( 'http_request_args', [ $this, 'download_package' ] );

		return $args;
	}


	public function after_install( $response, $hook_extra, $result ) {

		global $wp_filesystem;

		$install_directory = plugin_dir_path( (string) $this->file );
		$wp_filesystem->move( $result['destination'], $install_directory );
		$result['destination'] = $install_directory;

		if ( $this->active ) {
			activate_plugin( $this->basename );
		}

		return $result;
	}

}
