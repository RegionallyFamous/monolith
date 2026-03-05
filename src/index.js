/**
 * Monolith – Block editor registration.
 *
 * Registers the quick-view modal block, a Block Variation of the
 * WooCommerce Product Collection block, and Block Style Variations
 * for the modal.
 */

import './style.css';

import { registerBlockType, registerBlockVariation, registerBlockStyle } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';

import Edit from './edit';
import metadata from './block.json';

/**
 * Register the quick-view modal block.
 */
registerBlockType( metadata.name, {
	edit: Edit,
	save: () => null, // Dynamic block – rendered server-side via render.php.
} );

/**
 * Register a "Monolith Store" variation of the WooCommerce Product Collection
 * block. This appears as a named option in the block inserter so store owners
 * don't need to configure a generic Product Collection from scratch.
 */
registerBlockVariation( 'woocommerce/product-collection', {
	name: 'monolith/store',
	title: __( 'Monolith Store', 'monolith' ),
	description: __(
		'A product grid with quick-view modal, optimised for single-page browsing.',
		'monolith'
	),
	icon: 'store',
	attributes: {
		namespace: 'monolith/store',
		displayLayout: {
			type: 'flex',
			columns: 3,
		},
	},
	isActive: [ 'namespace' ],
	scope: [ 'inserter' ],
} );

/**
 * Register Block Style Variations for the modal.
 * Users can switch between styles in the Editor's Style panel.
 */
registerBlockStyle( 'monolith/quick-view-modal', {
	name: 'default',
	label: __( 'Default', 'monolith' ),
	isDefault: true,
} );

registerBlockStyle( 'monolith/quick-view-modal', {
	name: 'minimal',
	label: __( 'Minimal', 'monolith' ),
} );

registerBlockStyle( 'monolith/quick-view-modal', {
	name: 'fullscreen',
	label: __( 'Fullscreen', 'monolith' ),
} );
