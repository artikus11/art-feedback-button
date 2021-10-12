MicroModal.init();

const AFB_triggerEvent = ( target, name, detail ) => {
	const event = new CustomEvent( `afb_${ name }`, {
		bubbles: true,
		detail
	} );

	if ( typeof target === 'string' ) {
		target = document.querySelector( target );
	}

	target.dispatchEvent( event );
};


function AFB_viewModal( url, param ) {
	const status = function( response ) {
		if ( response.status !== 200 ) {
			return Promise.reject( new Error( response.statusText ) );
		}
		return Promise.resolve( response );
	};

	const json = function( response ) {
		return response.json();
	};

	let html = function( data ) {
		return data;
	};

	return fetch( url, param )
		.then( status )
		.then( json )
		.then( html )
		.catch( function( error ) {
			console.log( 'error', error );
		} );
}


function AFB_sendForm( form, button, modalWindow, modalWindowContent ) {
	form.addEventListener( 'submit', function( event ) {

		event.preventDefault();

		const FD = new FormData( form );
		const url = form.getAttribute( 'action' );

		button.setAttribute( 'disabled', 'disabled' );

		modalWindow.classList.add( 'preload' );

		(
			async() => {
				let response = await fetch( url, {
					method: 'POST',
					body: FD
				} );

				let result = await response.json();

				modalWindow.classList.remove( 'preload' );
				button.removeAttribute( 'disabled' );

				if ( response.ok === false ) {
					let arr = result.message;

					for ( let key in arr ) {
						let elEr = modalWindow.querySelector( 'label[for="' + key + '"]' );

						if ( result.data.status === 405 ) {
							elEr = form;
						}

						elEr.insertAdjacentHTML( 'beforeend', '<span class="error">' + arr[key] + '</span>' );

					}

					setTimeout( function() {
						let erMes = modalWindow.querySelectorAll( '.error' );

						erMes.forEach( function( item, i, erMes ) {
							item.remove();
						} );
					}, 5000 );
				}

				if ( result.status === 'success' ) {

					AFB_triggerEvent( form, 'send_success', result );

					modalWindowContent.innerHTML = '';
					modalWindowContent.insertAdjacentHTML( 'afterend',
						'<span class="success">' + result.message + '</span>' );

					setTimeout( function() {
						MicroModal.close( 'afb-modal' );
					}, 3000 );
				}

			}
		)();

	} );
}


function AFB_modalShow() {
	MicroModal.show( 'afb-modal', {
		debugMode: true,
		disableScroll: true,
		onShow: function( modal ) {
			const inputTel = modal.querySelector( 'input[type=tel]' );

			VMasker( inputTel ).maskPattern( inputTel.dataset.mask );

			AFB_triggerEvent( modal, 'open' );
		},
		onClose: function( modal ) {

			AFB_triggerEvent( modal, 'close' );

			modal.remove();

		},
		closeTrigger: 'data-afb-close',
		disableFocus: false,
		awaitCloseAnimation: true
	} );
}


const buttons = document.querySelectorAll( '.button-shortcode-js' );

buttons.forEach( buttonShortcode =>

	buttonShortcode.addEventListener( 'click', function( event ) {

		let thisButton = event.target;
		let url = thisButton.dataset.windowUrl;

		let dataSend = {
			emails: thisButton.dataset.afbEmails
		};

		this.setAttribute( 'disabled', 'disabled' );

		AFB_viewModal( url, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json;charset=utf-8'
			},
			body: JSON.stringify( dataSend )
		} ).then( function( result ) {

			document.body.insertAdjacentHTML( 'afterend', result.html );
			thisButton.removeAttribute( 'disabled' );

			AFB_modalShow();

			const modalWindow = document.querySelector( '.afb-modal__container' );
			const modalWindowContent = document.querySelector( '.afb-modal__content' );
			const form = document.querySelector( '.afb-modal-form' );
			const button = document.querySelector( '.js-send-modal-form' );

			AFB_sendForm( form, button, modalWindow, modalWindowContent );
		} );

	} )
);