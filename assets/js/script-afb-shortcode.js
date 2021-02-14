MicroModal.init();

function viewModal( url, param ) {
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


document.querySelector( '.button-shortcode-js' ).addEventListener( 'click', function( event ) {

	let thisButton = event.target;
	let url = thisButton.dataset.windowUrl;

	let dataSend = {
		emails: thisButton.dataset.afbEmails
	}

	this.setAttribute( 'disabled', 'disabled' );

	viewModal( url, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json;charset=utf-8'
		},
		body: JSON.stringify(dataSend)
	} ).then( function( result ) {

		thisButton.insertAdjacentHTML( 'afterend', result.html );
		thisButton.removeAttribute( 'disabled' );

		MicroModal.show( 'afb-modal', {
			debugMode: true,
			disableScroll: true,
			onShow: function( modal ) {
				VMasker( modal.querySelector( 'input[type=tel]' ) ).maskPattern( '9(999) 999-99-99' );
			},
			onClose: function( modal ) {
				modal.remove();
			},
			closeTrigger: 'data-afb-close',
			disableFocus: false,
			awaitCloseAnimation: true
		} );

		const modalWindow = document.querySelector( '.afb-modal__container' );
		const modalWindowContent = document.querySelector( '.afb-modal__content' );
		const form = document.querySelector( '.afb-modal-form' );

		document.querySelector( '.js-send-modal-form' ).addEventListener( 'click', function( event ) {


			const FD = new FormData( form );
			const url = form.getAttribute( 'action' );

			event.target.setAttribute( 'disabled', 'disabled' );
			modalWindow.classList.add( 'preload' );

			(
				async() => {
					let response = await fetch( url, {
						method: 'POST',
						body: FD
					} );

					let result = await response.json();

					if ( response.ok ) {
						modalWindow.classList.remove( 'preload' );
						event.target.removeAttribute( 'disabled' );
					}

					if ( result.status === 'error' ) {
						let arr = result.message;

						for ( let key in arr ) {
							let elEr = modalWindow.querySelector( 'label[for="' + key + '"]' );

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
	} );


} );



/*	(
 async() => {

 let response = await fetch( url, {
 method: 'POST',
 headers: {
 'Content-Type': 'application/json;charset=utf-8'
 }
 } );

 if ( response.ok ) {

 this.removeAttribute( 'disabled' );

 let result = await response.json();

 this.insertAdjacentHTML( 'afterend', result.html );

 MicroModal.show( 'callback-modal', {
 debugMode: true,
 disableScroll: true,
 onShow: function( modal ) {
 VMasker( modal.querySelector( 'input[type=tel]' ) ).maskPattern( '9(999) 999-99-99' );
 },
 onClose: function( modal ) {
 modal.remove();
 },
 closeTrigger: 'data-custom-close',
 disableFocus: false,
 awaitCloseAnimation: true
 } );

 document.querySelector( '.js-modal-close-trigger' ).addEventListener( 'click', function( event ) {
 MicroModal.close( 'callback-modal' );
 } );

 document.querySelector( '.js-send-modal-form' ).addEventListener( 'click', function( event ) {

 const modalWindow = document.querySelector( '.callback-modal__container' );
 const modalWindowContent = document.querySelector( '.callback-modal__content' );
 const form = document.querySelector( '.callback-modal-form' );
 const FD = new FormData( form );
 const url = form.getAttribute( 'action' );

 event.target.setAttribute( 'disabled', 'disabled' );
 modalWindow.classList.add( 'preload' );

 (
 async() => {
 let response = await fetch( url, {
 method: 'POST',
 body: FD
 } );

 let result = await response.json();

 if ( response.ok ) {
 modalWindow.classList.remove( 'preload' );
 event.target.removeAttribute( 'disabled' );
 }

 if ( result.status === 'error' ) {
 let arr = result.message;

 for ( let key in arr ) {
 let elEr = modalWindow.querySelector( 'label[for="' + key + '"]' );

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
 modalWindowContent.innerHTML = '';
 modalWindowContent.insertAdjacentHTML( 'afterend',
 '<span class="success">' + result.message + '</span>' );

 setTimeout( function() {
 MicroModal.close( 'callback-modal' );
 }, 3000 );
 }

 }
 )();

 } );

 } else {
 alert( 'Ошибка HTTP: ' + response.status );
 }

 }
 )();*/



