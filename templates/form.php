<?php
/**
 * Файл шаблона вывода формы
 *
 * @global $args
 * @package art-feedback-button
 */

?>
<form class="afb-modal-form" action="<?php echo rest_url( 'afb/v1/form' ); ?>">
	<div class="afb-modal-form__fields">
		<?php
		$fields = afb()->fields->get_form_fields();

		foreach ( $fields as $key => $field ) :
			afb()->fields->fields( $key, $field );
		endforeach;

		?>
	</div>
	<input
		name="afb-emails"
		type="hidden"
		value="<?php echo esc_attr( $args['emails'] ); ?>">

	<button type="button" class="afb-modal__btn afb-modal__btn-primary js-send-modal-form">Отправить</button>
</form>
