<?php

namespace ART\AFB;

class Fields {

	public function fields( $key, $args, $value = null ) {

		$defaults = [
			'type'              => 'text',
			'label'             => '',
			'description'       => '',
			'placeholder'       => '',
			'maxlength'         => false,
			'required'          => false,
			'autocomplete'      => false,
			'id'                => $key,
			'class'             => [],
			'label_class'       => [],
			'input_class'       => [],
			'return'            => false,
			'options'           => [],
			'custom_attributes' => [],
			'validate'          => [],
			'default'           => '',
			'autofocus'         => '',

		];

		$args = wp_parse_args( $args, $defaults );

		$args = apply_filters( 'afp_form_field_args', $args, $key, $value );

		$required = '';

		if ( $args['required'] ) {
			$args['class'][] = 'validate-required';

			$required = sprintf( '&nbsp;<abbr class="required" title="%1$s">*</abbr>', esc_attr__( 'required', 'afp' ) );
		}

		if ( is_string( $args['label_class'] ) ) {
			$args['label_class'] = [ $args['label_class'] ];
		}

		if ( is_null( $value ) ) {
			$value = $args['default'];
		}

		// Custom attribute handling.
		$custom_attributes = [];

		$args['custom_attributes'] = array_filter( (array) $args['custom_attributes'], 'strlen' );

		if ( $args['maxlength'] ) {
			$args['custom_attributes']['maxlength'] = absint( $args['maxlength'] );
		}

		if ( ! empty( $args['autocomplete'] ) ) {
			$args['custom_attributes']['autocomplete'] = $args['autocomplete'];
		}

		if ( true === $args['autofocus'] ) {
			$args['custom_attributes']['autofocus'] = 'autofocus';
		}

		if ( $args['description'] ) {
			$args['custom_attributes']['aria-describedby'] = $args['id'] . '-description';
		}

		if ( ! empty( $args['custom_attributes'] ) && is_array( $args['custom_attributes'] ) ) {
			foreach ( $args['custom_attributes'] as $attribute => $attribute_value ) {
				$custom_attributes[] = sprintf( '%1$s="%2$s"', esc_attr( $attribute ), esc_attr( $attribute_value ) );
			}
		}

		if ( ! empty( $args['validate'] ) ) {
			foreach ( $args['validate'] as $validate ) {
				$args['class'][] = sprintf( 'validate-%s', $validate );
			}
		}

		$field    = '';
		$label_id = $args['id'];

		$field_container = '<div class="afb-field-wrapper %1$s" id="%2$s">%3$s</div>';

		switch ( $args['type'] ) {
			case 'text':
			case 'password':
			case 'datetime':
			case 'datetime-local':
			case 'date':
			case 'month':
			case 'time':
			case 'week':
			case 'number':
			case 'email':
			case 'url':
			case 'file':
			case 'tel':
				$field .= $this->get_text( $args, $key, $value, $custom_attributes, $field );

				break;
			case 'select':
				[ $field, $args ] = $this->get_select( $args, $custom_attributes, $value, $key );

				break;
			case 'radio':
				[ $label_id, $args, $field ] = $this->get_radio( $args, $label_id, $key, $custom_attributes, $value, $field );

				break;
			case 'textarea':
				[ $args, $field ] = $this->get_textarea( $key, $args, $custom_attributes, $value, $field );

				break;
			case 'checkbox':
				$field = $this->get_checkbox( $args, $custom_attributes, $key, $value, $required );

				break;
		}

		if ( ! empty( $field ) ) {
			$field_html = '';

			if ( $args['label'] && 'checkbox' !== $args['type'] ) {
				$field_html .= sprintf(
					'<label for="%s" class="%s">%s%s</label>',
					esc_attr( $label_id ),
					esc_attr( implode( ' ', $args['label_class'] ) ),
					$args['label'],
					$required
				);
			}

			$field_html .= '<span class="afp-input-wrapper">' . $field;

			if ( $args['description'] ) {
				$field_html .= sprintf(
					'<span class="description" id="%1$s-description" aria-hidden="true">%2$s</span>',
					esc_attr( $args['id'] ),
					wp_kses_post( $args['description'] )
				);
			}

			$field_html .= '</span>';

			$container_class = esc_attr( implode( ' ', $args['class'] ) );
			$container_id    = esc_attr( $args['id'] ) . '_field';
			$field           = sprintf( $field_container, $container_class, $container_id, $field_html );
		}

		if ( ! $args['return'] ) {
			echo $field; // WPCS: XSS ok.
		}

		return $field;
	}


	/**
	 * @param         $args
	 * @param         $key
	 * @param         $value
	 * @param  array  $custom_attributes
	 * @param  string $field
	 *
	 * @return string
	 */
	protected function get_text( $args, $key, $value, array $custom_attributes, string $field ): string {

		return sprintf(
			'<input type="%1$s" class="input-text %2$s" name="%3$s" id="%4$s" placeholder="%5$s"  value="%6$s" %7$s />',
			esc_attr( $args['type'] ),
			esc_attr( implode( ' ', $args['input_class'] ) ),
			esc_attr( $key ),
			esc_attr( $args['id'] ),
			esc_attr( $args['placeholder'] ),
			esc_attr( $value ),
			implode( ' ', $custom_attributes )
		);

	}


	/**
	 * @param        $args
	 * @param  array $custom_attributes
	 * @param        $value
	 * @param        $key
	 *
	 * @return array
	 */
	protected function get_select( $args, array $custom_attributes, $value, $key ): array {

		$field   = '';
		$options = '';

		if ( ! empty( $args['options'] ) ) {
			foreach ( $args['options'] as $option_key => $option_text ) {
				if ( '' === $option_key ) {
					// If we have a blank option, select2 needs a placeholder.
					if ( empty( $args['placeholder'] ) ) {
						$args['placeholder'] = $option_text ? : __( 'Choose an option', 'afp' );
					}
					$custom_attributes[] = 'data-allow_clear="true"';
				}
				$options .= sprintf(
					'<option value="%1$s" %2$s>%3$s</option>',
					esc_attr( $option_key ),
					selected( $value, $option_key, false ),
					esc_attr( $option_text )
				);
			}

			$field .= sprintf(
				'<select name="%1$s" id="%2$s" class="select %3$s" %4$s data-placeholder="%5$s">
							%6$s
						</select>',
				esc_attr( $key ),
				esc_attr( $args['id'] ),
				esc_attr( implode( ' ', $args['input_class'] ) ),
				implode( ' ', $custom_attributes ),
				esc_attr( $args['placeholder'] ),
				$options
			);
		}

		return [ $field, $args ];
	}


	/**
	 * @param         $args
	 * @param  string $label_id
	 * @param         $key
	 * @param  array  $custom_attributes
	 * @param         $value
	 * @param  string $field
	 *
	 * @return array
	 */
	protected function get_radio( $args, string $label_id, $key, array $custom_attributes, $value, string $field ): array {

		$label_id .= '_' . current( array_keys( $args['options'] ) );

		if ( ! empty( $args['options'] ) ) {
			foreach ( $args['options'] as $option_key => $option_text ) {
				$field .= sprintf(
					'<input type="radio" class="input-radio %1$s" value="%2$s" name="%3$s" %4$s id="%5$s_%6$s"%7$s />',
					esc_attr( implode( ' ', $args['input_class'] ) ),
					esc_attr( $option_key ),
					esc_attr( $key ),
					implode( ' ', $custom_attributes ),
					esc_attr( $args['id'] ),
					esc_attr( $option_key ),
					checked(
						$value,
						$option_key,
						false
					)
				);
				$field .= sprintf(
					'<label for="%1$s_%2$s" class="radio %3$s">%4$s</label>',
					esc_attr( $args['id'] ),
					esc_attr( $option_key ),
					implode( ' ', $args['label_class'] ),
					$option_text
				);
			}
		}

		return [ $label_id, $args, $field ];
	}


	/**
	 * @param         $key
	 * @param         $args
	 * @param  array  $custom_attributes
	 * @param         $value
	 * @param  string $field
	 *
	 * @return array
	 */
	protected function get_textarea( $key, $args, array $custom_attributes, $value, string $field ): array {

		$field .= sprintf(
			'<textarea name="%1$s" class="input-text %2$s" id="%3$s" placeholder="%4$s" %5$s%6$s%7$s>%8$s</textarea>',
			esc_attr( $key ),
			esc_attr( implode( ' ', $args['input_class'] ) ),
			esc_attr( $args['id'] ),
			esc_attr( $args['placeholder'] ),
			empty( $args['custom_attributes']['rows'] ) ? ' rows="2"' : '',
			empty( $args['custom_attributes']['cols'] ) ? ' cols="5"' : '',
			implode( ' ', $custom_attributes ),
			esc_textarea( $value )
		);

		return [ $args, $field ];
	}


	/**
	 * @param         $args
	 * @param  array  $custom_attributes
	 * @param         $key
	 * @param         $value
	 * @param  string $required
	 *
	 * @return string
	 */
	protected function get_checkbox( $args, array $custom_attributes, $key, $value, string $required ): string {

		return sprintf(
			'<label class="checkbox %1$s" %2$s><input type="%3$s" class="input-checkbox %4$s" name="%5$s" id="%6$s" value="1" %7$s /> %8$s%9$s</label>',
			implode( ' ', $args['label_class'] ),
			implode( ' ', $custom_attributes ),
			esc_attr( $args['type'] ),
			esc_attr( implode( ' ', $args['input_class'] ) ),
			esc_attr( $key ),
			esc_attr( $args['id'] ),
			checked( $value, 1, false ),
			$args['label'],
			$required
		);
	}


	public function get_form_fields(): array {

		return [
			'afb-name'  => [
				'type'        => 'text',
				'label'       => 'Ваше имя',
				'placeholder' => 'Ваше имя',
				'required'    => true,
			],
			'afb-email' => [
				'type'        => 'email',
				'label'       => 'Ваш email',
				'placeholder' => 'info@mail.com',
				'required'    => true,
			],
			'afb-phone' => [
				'type'              => 'tel',
				'label'             => 'Ваш телефон',
				'placeholder'       => '7 (999) 999-99-99',
				'required'          => true,
				'custom_attributes' => [
					'data-mask' => '9 (999) 999-99-99',
				],
			],
		];
	}

}