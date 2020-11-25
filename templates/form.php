<div id="callback-modal" class="callback-modal" aria-hidden="true">
	<div class="callback-modal__overlay" tabindex="-1" data-custom-close>
		<div
			class="callback-modal__container w-90 w-40-ns"
			role="dialog"
			aria-modal="true"
			aria-labelledby="modal-title">

			<header class="callback-modal__header">
				<div id="modal-title" class="callback-modal__title">Заказать звонок</div>
				<button
					type="button"
					class="callback-modal__close js-modal-close-trigger"
					aria-label="Close modal"
					data-micromodal-close>&#215;
				</button>
			</header>

			<div id="modal-content" class="callback-modal__content">
				<form class="callback-modal-form" action="<?php echo rest_url( 'callback/api/v1/form' ); ?>">
					<div class="callback-modal-form__fields">
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
					<button type="button" class="callback-modal__btn callback-modal__btn-primary js-send-modal-form">Отправить</button>
				</form>

			</div>
			<footer class="callback-modal__footer">


			</footer>

		</div>
	</div>
</div>