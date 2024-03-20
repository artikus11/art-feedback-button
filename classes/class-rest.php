<?php

namespace ART\AFB;

use WP_Error;
use WP_REST_Request;

class Rest {

	protected Core $core;


	public function __construct( $core ) {

		$this->core = $core;
	}


	public function setup_hooks(): void {

		add_action( 'rest_api_init', [ $this, 'route' ] );
	}


	public function route(): void {

		register_rest_route(
			'afb/v1',
			'/window',
			[
				'methods'             => [ 'POST' ],
				'callback'            => [ $this, 'modal_window' ],
				'permission_callback' => '__return_true',
			]
		);

		register_rest_route(
			'afb/v1',
			'/form',
			[
				'methods'             => [ 'POST' ],
				'callback'            => [ $this, 'form' ],
				'permission_callback' => '__return_true',
			]
		);
	}


	/**
	 * @param  WP_REST_Request $request
	 *
	 * @return array
	 */
	public function modal_window( WP_REST_Request $request ): array {

		ob_start();

		load_template(
			afb()->get_template( 'modal-content.php' ),
			true,
			[
				'emails' => $request->get_param( 'emails' ) ?? '',
			]
		);

		return [
			'html' => ob_get_clean(),

		];
	}


	/**
	 * @param  WP_REST_Request $request
	 *
	 * @return string[]|\WP_Error
	 */
	public function form( WP_REST_Request $request ) {

		$fields = $request->get_params();

		$error = $this->validation( $fields );

		if ( ! empty ( $error ) ) {
			return new WP_Error( 'error', $error, [ 'status' => 406 ] );
		}

		$send = $this->send( $fields );

		if ( ! $send ) {
			do_action( 'afb_error_send_mail', $fields );

			return new WP_Error( 'error', 'Что-то пошло не так', [ 'status' => 405 ] );
		}

		do_action( 'afb_send_mail', $fields );

		return [
			'status'  => 'success',
			'message' => apply_filters( 'afb_message_success', 'Сообщение успешно отправлено.' ),
		];
	}


	/**
	 * @param $fields
	 *
	 * @return bool
	 */
	private function send( $fields ): bool {

		$email_to = [ get_option( 'admin_email' ) ];

		if ( $fields['afb-emails'] ) {
			$email_to = array_map( 'trim', explode( ',', base64_decode( $fields['afb-emails'] ) ) );
		}

		$email_content = $this->email_fields( $fields );

		ob_start();

		load_template(
			$this->core->get_template( 'email.php' ),
			false,
			[
				'fields' => $email_content,
			]
		);

		$content = apply_filters( 'afb_email_content', ob_get_clean(), );

		$subject = apply_filters( 'afb_email_subject', 'Заявка на обратный звонок с сайта ' );

		return $this->wp_mail( $email_to, $subject, $content );
	}


	/**
	 * @param  string|[] $to
	 * @param  string $subject
	 * @param  string $message
	 *
	 * @return bool
	 */
	private function wp_mail( $to, string $subject, string $message ): bool {

		$headers = [
			'From: Заявка на обратный звонок с сайта <info@' . parse_url( get_option( 'home' ), PHP_URL_HOST ) . '>',
			'content-type: text/html',
		];

		return wp_mail( $to, $subject, $message, $headers );
	}


	public function validation( $fields ): array {

		$fields_default = $this->core->fields->get_form_fields();

		$errors   = [];
		$required = [];

		foreach ( $fields_default as $name => $value ) {
			if ( false !== $value['required'] ) {
				$required[ $name ] = $value['notice'];
			}
		}

		foreach ( $required as $key => $item ) {

			if ( empty( $fields[ $key ] ) ) {
				$errors[ $key ] = $item;
			}

			if ( false !== strpos( $key, 'email' ) && ! is_email( $fields[ $key ] ) ) {
				$errors[ $key ] = 'Указана некорректная почта';
			}
		}

		return $errors;
	}


	/**
	 * @param $fields
	 *
	 * @return array
	 */
	protected function email_fields( $fields ): array {

		$fields_default = $this->core->fields->get_form_fields();

		$email_fields  = [];
		$email_content = [];

		foreach ( $fields_default as $field => $value ) {
			$email_fields[ $field ] = $value['email_label'];
		}

		foreach ( $email_fields as $key => $item ) {
			$email_content[ $item ] = $fields[ $key ];
		}

		return $email_content;
	}

}
