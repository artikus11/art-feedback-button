<?php
/**
 * Файл шаблона модального окна
 *
 * @global $args
 * @package art-feedback-button
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div id="afb-modal" class="afb-modal" aria-hidden="true">
	<div class="afb-modal__overlay" tabindex="-1" data-afb-close>
		<div
			class="afb-modal__container"
			role="dialog"
			aria-modal="true"
			aria-labelledby="modal-title">

			<header class="afb-modal__header">
				<div id="modal-title" class="afb-modal__title">Заказать звонок</div>
				<button
					type="button"
					class="afb-modal__close js-modal-close-trigger"
					aria-label="Close modal"
					data-afb-close>&#215;
				</button>
			</header>

			<div id="modal-content" class="afb-modal__content">
				<?php
				load_template(
					afb()->get_template( 'form.php' ),
					true,
					[
						'emails' => $args['emails'],
					]
				);
				?>

			</div>
			<footer class="afb-modal__footer"></footer>
		</div>
	</div>
</div>