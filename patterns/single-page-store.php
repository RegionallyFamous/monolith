<?php
/**
 * Title: Monolith Store
 * Slug: monolith/single-page-store
 * Categories: woocommerce
 * Description: Full single-page store layout — product filters, product grid with quick-view modal, and a mini cart. Install Monolith and add this pattern to any page for an instant single-page shopping experience.
 * Keywords: monolith, store, single page, woocommerce, products, shop
 * Viewport Width: 1200
 */

?>
<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group">

	<!-- wp:woocommerce/mini-cart /-->

	<!-- wp:woocommerce/product-filters /-->

	<!-- wp:woocommerce/product-collection {"namespace":"monolith/store","columns":3,"displayLayout":{"type":"flex","columns":3}} -->
		<!-- wp:woocommerce/product-template -->
			<!-- wp:woocommerce/product-image {"aspectRatio":"1"} /-->
			<!-- wp:woocommerce/product-title {"level":3} /-->
			<!-- wp:woocommerce/product-price /-->
			<!-- wp:woocommerce/add-to-cart-with-options /-->
		<!-- /wp:woocommerce/product-template -->

		<!-- wp:woocommerce/product-no-results -->
			<!-- wp:paragraph {"align":"center"} -->
			<p class="has-text-align-center"><?php esc_html_e( 'No products found.', 'monolith' ); ?></p>
			<!-- /wp:paragraph -->
		<!-- /wp:woocommerce/product-no-results -->

		<!-- wp:woocommerce/product-collection-toolbar /-->

		<!-- wp:query-pagination {"layout":{"type":"flex","justifyContent":"center"}} -->
			<!-- wp:query-pagination-previous /-->
			<!-- wp:query-pagination-numbers /-->
			<!-- wp:query-pagination-next /-->
		<!-- /wp:query-pagination -->

	<!-- /wp:woocommerce/product-collection -->

</div>
<!-- /wp:group -->
