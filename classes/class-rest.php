<?php

namespace ART\AFB;

use WP_REST_Request;

class Rest {

	public function setup_hooks(): void {

		add_action( 'rest_api_init', [ $this, 'route' ] );
	}


	public function route() {

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
			afb()->get_template( 'modal.php' ),
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
	 * @return array
	 */
	public function form( WP_REST_Request $request ): array {

		$fields = $request->get_params();

		$error = $this->validation( $fields );

		if ( ! empty ( $error ) ) {
			return [
				'status'  => 'error',
				'message' => $error,
			];
		}

		$send = $this->send( $fields );

		if ( $send ) {
			return [
				'status'  => 'success',
				'message' => 'Сообщение успешно отправлено.',
			];
		}

	}


	/**
	 * @param $fields
	 *
	 * @return bool
	 */
	private function send( $fields ): bool {

		$email_to = [];

		if ( ! $email_to ) {
			$email_to[] = get_option( 'admin_email' );
		}

		if ( $fields['afb-emails'] ) {
			$email_to = base64_decode( $fields['afb-emails'] );
		}

		ob_start();

		load_template(
			afb()->get_template( 'email.php' ),
			false,
			[]
		);

		$content = ob_get_clean();

		foreach ( $fields as $key => $field ) {
			$content = str_replace( '[' . $key . ']', $field, $content );
		}

		return $this->wp_mail( $email_to, 'Заявка на обратный звонок с сайта ', $content );
	}


	/**
	 * @param $to
	 * @param $subject
	 * @param $message
	 *
	 * @return bool
	 */
	private function wp_mail( $to, $subject, $message ): bool {

		$headers = [
			'From: Заявка на обратный звонок с сайта <info@' . parse_url( get_option( 'home' ), PHP_URL_HOST ) . '>',
			'content-type: text/html',
		];

		return wp_mail( $to, $subject, $message, $headers );
	}


	public function validation( $fields ): array {

		$error = [];

		$required = [
			'acip-name'  => 'Это обязательное поле. Укажите Имя',
			//'acip-email' => 'Это обязательное поле. Укажите Email',
			'acip-phone' => 'Это обязательное поле. Укажите Телефон',
		];

		foreach ( $required as $key => $item ) {

			if ( empty( $fields[ $key ] ) || ! isset( $fields[ $key ] ) ) {
				$error[ $key ] = $item;
			}

			if ( 'acip-email' === $key && ! empty( $fields[ $key ] ) && ! is_email( $fields[ $key ] ) ) {
				$error[ $key ] = 'Указана некорректная почта';
			}
		}

		return $error;
	}

}
