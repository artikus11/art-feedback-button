<form class="afb-modal-form" action="<?php echo rest_url( 'afb/v1/form' ); ?>">
	<div class="afb-modal-form__fields">
		<?php
		$fields = afb()->fields->get_form_fields();

		foreach ( $fields as $key => $field ) :
			afb()->fields->fields( $key, $field );
		endforeach;
		error_log( print_r( $args['emails'], 1 ) );
		?>
		<!--<div class="afb-field-wrapper">
			<label
				for="acip-name"
				class="">Имя</label>
			<input
				id="acip-name"
				name="acip-name"
				class=""
				type="text"
				placeholder="Имя">
		</div>-->
		<!--<div class="acip-field-wrapper">
			<label
				for="acip-email"
				class="">Email</label>
			<input
				id="acip-email"
				name="acip-email"
				class=""
				type="email"
				placeholder="Email">
		</div>-->
		<!--<div class="afb-field-wrapper">
			<label
				for="acip-phone"
				class="">Телефон</label>
			<input
				id="acip-phone"
				class=""
				name="acip-phone"
				type="tel"
				placeholder="7 (999) 999-99-99"
				data-mask="9 (999) 999-99-99">
		</div>-->
	</div>
	<input
		name="afb-emails"
		type="hidden"
		value="<?php echo esc_attr( $args['emails'] ); ?>">

	<button type="button" class="afb-modal__btn afb-modal__btn-primary js-send-modal-form">Отправить</button>
</form>
