<?php
/**
 * Файл шаблона вывода кнопки
 *
 * @global $args
 * @package art-feedback-button
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! $args ) {
	return;
}
?>

<button
	type="button"
	class="button button--shortcode button-shortcode-js <?php echo esc_attr( $args['class'] ); ?>"
	data-window-url="<?php echo esc_attr( $args['url'] ); ?>"
	data-afb-open
	data-afb-emails="<?php echo esc_attr( $args['emails'] ); ?>"><?php echo esc_html( $args['label'] ); ?></button>
