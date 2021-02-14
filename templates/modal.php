<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div id="afb-modal" class="afb-modal" aria-hidden="true">
	<div class="afb-modal__overlay" tabindex="-1" data-afb-close>
		<div
			class="afb-modal__container w-90 w-40-ns"
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
				<form class="afb-modal-form" action="<?php echo rest_url( 'afb/v1/form' ); ?>">
					<div class="afb-modal-form__fields">
						<div class="acip-field-wrapper">
							<label
								for="acip-name"
								class="">Имя</label>
							<input
								id="acip-name"
								name="acip-name"
								class=""
								type="text"
								placeholder="Имя">
						</div>
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
						<div class="acip-field-wrapper">
							<label
								for="acip-phone"
								class="">Телефон</label>
							<input
								id="acip-phone"
								class=""
								name="acip-phone"
								type="tel"
								placeholder="7(999) 999-99-99">
						</div>
					</div>
					<input
						name="afb-emails"
						type="hidden"
						value="<?php echo esc_attr( $args['emails'] ); ?>">
					<button type="button" class="afb-modal__btn afb-modal__btn-primary js-send-modal-form">Отправить</button>
				</form>

			</div>
			<footer class="afb-modal__footer">


			</footer>

		</div>
	</div>
</div>