<?php
/**
 * Server-side render for the Monolith quick-view modal block.
 *
 * This file outputs the modal shell with Interactivity API directives.
 * The modal content (product data) is populated client-side via view.js
 * after WooCommerce fires the wc-blocks_viewed_product DOM event.
 *
 * @package Monolith
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner block content.
 * @var WP_Block $block      Block instance.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wc_get_checkout_url' ) ) {
	return;
}

$monolith_nonce   = wp_create_nonce( 'wc_store_api' );
$monolith_api_url = rest_url( 'wc/store/v1' );

wp_interactivity_config(
	'monolith',
	array(
		'apiUrl' => $monolith_api_url,
		'nonce'  => $monolith_nonce,
	)
);

wp_interactivity_state(
	'monolith',
	array(
		'modalOpen'    => false,
		'loading'      => false,
		'product'      => null,
		'announcement' => '',
	)
);
?>

<div
	<?php echo wp_kses_data( get_block_wrapper_attributes() ); ?>
	data-wp-interactive="monolith"
	data-wp-run="callbacks.syncAriaHidden"
>
	<?php /* Screen-reader live region for async status announcements. */ ?>
	<div
		class="screen-reader-text"
		aria-live="polite"
		aria-atomic="true"
		data-wp-text="state.announcement"
	></div>

	<?php /* Overlay — closes modal on click. */ ?>
	<div
		class="monolith-modal__overlay"
		aria-hidden="true"
		data-wp-class--is-active="state.modalOpen"
		data-wp-on--click="actions.closeModal"
	></div>

	<?php /* Modal dialog. */ ?>
	<div
		class="monolith-modal"
		role="dialog"
		aria-modal="true"
		aria-labelledby="monolith-modal-title"
		data-wp-class--is-open="state.modalOpen"
		data-wp-on--keydown="actions.handleKeydown"
		tabindex="-1"
	>
		<?php /* Close button. */ ?>
		<button
			class="monolith-modal__close"
			aria-label="<?php esc_attr_e( 'Close product preview', 'monolith' ); ?>"
			data-wp-on--click="actions.closeModal"
		>
			<span aria-hidden="true">&times;</span>
		</button>

		<?php /* Loading state. */ ?>
		<div
			class="monolith-modal__loading"
			aria-hidden="true"
			data-wp-class--is-visible="state.loading"
		>
			<span class="screen-reader-text"><?php esc_html_e( 'Loading product…', 'monolith' ); ?></span>
		</div>

		<?php /* Product content — hidden while loading. */ ?>
		<div
			class="monolith-modal__content"
			data-wp-class--is-visible="!state.loading"
		>
			<?php /* Product image. */ ?>
			<div class="monolith-modal__image">
				<img
					data-wp-bind--src="state.product?.images[0]?.src"
					data-wp-bind--alt="state.product?.images[0]?.alt"
					loading="lazy"
					decoding="async"
					width="600"
					height="600"
				/>
			</div>

			<?php /* Product details. */ ?>
			<div class="monolith-modal__details">
				<h2
					id="monolith-modal-title"
					class="monolith-modal__title"
					data-wp-text="state.product?.name"
				></h2>

				<div
					class="monolith-modal__price"
					data-wp-html="state.product?.prices?.price_html"
				></div>

				<div
					class="monolith-modal__description"
					data-wp-html="state.product?.short_description"
				></div>

				<div class="monolith-modal__actions">
					<a
						class="wp-block-button__link monolith-modal__view-full"
						data-wp-bind--href="state.product?.permalink"
					>
						<?php esc_html_e( 'View full details', 'monolith' ); ?>
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
