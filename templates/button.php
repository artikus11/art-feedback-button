<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! $args ) {
	return;
}
?>

<button
	type="button"
	class="button button-callback-shortcode button-callback-shortcode-js <?php echo esc_attr( $args['class'] ); ?>"
	data-callback-url="<?php echo esc_attr( $args['url'] ); ?>"
>
	<?php echo esc_html( $args['label'] ); ?>
</button>
