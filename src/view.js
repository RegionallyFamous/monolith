/**
 * Monolith – Interactivity API store.
 *
 * Intercepts WooCommerce product clicks, fetches product data from the
 * Store API, and drives the quick-view modal. Also handles:
 *   – View Transitions API (card → modal morphing animation)
 *   – WCAG 2.1 AA focus trap and keyboard navigation
 *   – URL hash routing (#product-{id})
 *   – Screen-reader aria-live announcements
 */

import { store, getConfig } from '@wordpress/interactivity';
import { __, sprintf } from '@wordpress/i18n';

const { apiUrl, nonce } = getConfig( 'monolith' );

/**
 * Fetch a product from the WooCommerce Store API.
 *
 * @param {number} productId
 * @return {Promise<Object>} Product data.
 */
async function fetchProduct( productId ) {
	const response = await fetch( `${ apiUrl }/products/${ productId }`, {
		headers: {
			'X-WC-Store-API-Nonce': nonce,
			'Content-Type': 'application/json',
		},
	} );

	if ( ! response.ok ) {
		throw new Error( `Failed to fetch product ${ productId }` );
	}

	return response.json();
}

/**
 * Return all focusable elements within a container.
 *
 * @param {HTMLElement} container
 * @return {HTMLElement[]}
 */
function getFocusableElements( container ) {
	return Array.from(
		container.querySelectorAll(
			'a[href], button:not([disabled]), input:not([disabled]), ' +
				'select:not([disabled]), textarea:not([disabled]), ' +
				'[tabindex]:not([tabindex="-1"])'
		)
	).filter( ( el ) => ! el.closest( '[aria-hidden="true"]' ) );
}

/** Reference to the element that triggered the modal open. */
let triggerElement = null;

/** Reference to the open modal element. */
let modalElement = null;

const { state, actions } = store( 'monolith', {
	state: {
		modalOpen: false,
		loading: false,
		product: null,
		announcement: '',
	},

	actions: {
		/**
		 * Open the modal for the given product ID.
		 * Called by the wc-blocks_viewed_product DOM event listener.
		 *
		 * @param {CustomEvent} event
		 */
		*openModal( event ) {
			event.preventDefault();

			const { productId } = event.detail;
			if ( ! productId ) return;

			// Store the element that triggered the open so focus can return on close.
			triggerElement =
				event.target?.closest( '[data-product-id]' ) ||
				document.activeElement;

			// Start View Transitions animation if the browser supports it.
			const card = event.target?.closest( '[data-product-id]' );

			const doOpen = () => {
				state.modalOpen = true;
				state.loading = true;
				state.product = null;
				state.announcement = __( 'Loading product…', 'monolith' );
				document.documentElement.classList.add( 'monolith-modal-open' );
			};

			if ( card && document.startViewTransition ) {
				card.style.viewTransitionName = 'monolith-active-product';
				const transition = document.startViewTransition( doOpen );
				yield transition.ready;
				card.style.viewTransitionName = '';
			} else {
				doOpen();
			}

			// Update URL hash for shareability and browser history.
			history.pushState( { monolithProductId: productId }, '', `#product-${ productId }` );

			// Fetch product data from Store API.
			try {
				const product = yield fetchProduct( productId );
				state.product = product;
				state.loading = false;
				state.announcement = product.name
					? /* translators: %s: product name */
					  sprintf( __( 'Viewing %s', 'monolith' ), product.name )
					: __( 'Product loaded.', 'monolith' );
			} catch ( error ) {
				state.loading = false;
				state.modalOpen = false;
				state.announcement = __( 'Could not load product. Please try again.', 'monolith' );
				document.documentElement.classList.remove( 'monolith-modal-open' );
				// Restore URL without adding a spurious history entry.
				history.replaceState( {}, '', window.location.pathname );
				return;
			}

			// Move focus into the modal after it opens.
			requestAnimationFrame( () => {
				if ( ! state.modalOpen ) return;
				modalElement = document.querySelector( '.monolith-modal' );
				if ( modalElement ) {
					modalElement.focus();
				}
			} );
		},

		/**
		 * Close the modal and return focus to the trigger element.
		 */
		closeModal() {
			if ( ! state.modalOpen ) return;

			state.modalOpen = false;
			state.product = null;
			state.announcement = __( 'Product preview closed.', 'monolith' );
			document.documentElement.classList.remove( 'monolith-modal-open' );

			// Restore URL.
			history.pushState( {}, '', window.location.pathname );

			// Return focus to the element that opened the modal.
			requestAnimationFrame( () => {
				if ( triggerElement ) {
					triggerElement.focus();
					triggerElement = null;
				}
			} );
		},

		/**
		 * Handle keyboard events inside the modal:
		 *   – Escape: close
		 *   – Tab / Shift+Tab: trap focus inside the modal
		 *
		 * @param {KeyboardEvent} event
		 */
		handleKeydown( event ) {
			if ( ! state.modalOpen ) return;

			if ( 'Escape' === event.key ) {
				actions.closeModal();
				return;
			}

			if ( 'Tab' === event.key ) {
				const modal = event.currentTarget;
				const focusable = getFocusableElements( modal );

				if ( ! focusable.length ) {
					event.preventDefault();
					return;
				}

				const first = focusable[ 0 ];
				const last = focusable[ focusable.length - 1 ];

				if ( event.shiftKey ) {
					if ( document.activeElement === first ) {
						event.preventDefault();
						last.focus();
					}
				} else if ( document.activeElement === last ) {
					event.preventDefault();
					first.focus();
				}
			}
		},
	},

	callbacks: {
		/**
		 * Initialise listeners when the block mounts.
		 * Runs once when the directive element is first processed.
		 */
		init() {
			// Intercept WooCommerce's product-click navigation event.
			document.addEventListener( 'wc-blocks_viewed_product', actions.openModal );

			// Handle browser back/forward to close modal.
			window.addEventListener( 'popstate', ( event ) => {
				if ( state.modalOpen && ! event.state?.monolithProductId ) {
					// Close without pushing another history entry.
					state.modalOpen = false;
					state.product = null;
					state.announcement = __( 'Product preview closed.', 'monolith' );
					document.documentElement.classList.remove( 'monolith-modal-open' );
					if ( triggerElement ) {
						triggerElement.focus();
						triggerElement = null;
					}
				}
			} );

			// If the page loads with a product hash (e.g. shared link), open the modal.
			const hash = window.location.hash;
			const match = hash.match( /^#product-(\d+)$/ );
			if ( match ) {
				const syntheticEvent = new CustomEvent( 'wc-blocks_viewed_product', {
					detail: { productId: parseInt( match[ 1 ], 10 ) },
				} );
				document.dispatchEvent( syntheticEvent );
			}
		},

		/**
		 * Toggle aria-hidden on the page wrapper when the modal opens/closes.
		 * This hides background content from screen readers while modal is open.
		 */
		syncAriaHidden() {
			const page = document.getElementById( 'page' ) || document.body;
			if ( page ) {
				if ( state.modalOpen ) {
					page.setAttribute( 'aria-hidden', 'true' );
				} else {
					page.removeAttribute( 'aria-hidden' );
				}
			}
		},
	},
} );
