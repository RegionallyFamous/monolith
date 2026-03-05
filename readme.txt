=== Monolith ===
Contributors: RegionallyFamous
Tags: woocommerce, single page store, quick view, interactivity api, block theme
Requires at least: 6.8
Tested up to: 6.8
Requires PHP: 8.0
Stable tag: 1.0.0
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
WC requires at least: 10.4
WC tested up to: 10.4

Transforms a WooCommerce block-theme site into a single-page store. Browse, quick-view, add to cart — all without leaving the homepage.

== Description ==

Monolith turns your WooCommerce store into a seamless single-page shopping experience. Customers browse products, open a quick-view modal, and add to cart — all on the homepage with no page reloads.

**How it works**

1. Install Monolith alongside WooCommerce on any block theme.
2. Monolith automatically inserts its quick-view modal into every Product Collection block via Block Hooks — no editor setup required.
3. When a customer clicks a product, a full-details modal slides in using the View Transitions API. The URL updates for shareability.
4. Add to cart and checkout work through WooCommerce's native blocks — fully compatible with all payment gateways.

**Key features**

* **Zero configuration** — Block Hooks auto-insert the modal everywhere. Just activate the plugin.
* **"Monolith Store" block pattern** — Insert a complete store layout (filters + grid + mini cart) in one click.
* **Three modal styles** — Default, Minimal, and Fullscreen. Switchable from the Editor Style panel.
* **Automatic theme matching** — Inherits colors, typography, and spacing from your active theme via `theme.json` CSS variables.
* **View Transitions animation** — Product cards morph into the modal natively in supported browsers.
* **Accessible** — WCAG 2.1 AA compliant. Focus trap, Escape key, `aria-live` announcements.
* **Fast** — Block-scoped CSS loads only on pages that need it. No external JavaScript dependencies.

**Modern APIs used**

* WordPress Interactivity API
* WooCommerce Store API
* Block Hooks
* Block Patterns & Block Variations
* Block Style Variations
* `theme.json` CSS variables
* `wp_enqueue_block_style()`
* View Transitions API
* CSS `@starting-style`
* Speculation Rules API (WordPress 6.8)

== Installation ==

1. Make sure WooCommerce is installed and active.
2. Upload the `monolith` folder to `/wp-content/plugins/`.
3. Activate the plugin from the **Plugins** screen.
4. That's it — the modal automatically attaches to your existing Product Collection blocks.

**Optional: full store layout**

For a new page or homepage, open the Block Editor, click **+**, search for **Monolith Store**, and insert the pattern. It includes product filters, a product grid, and a mini cart.

== Frequently Asked Questions ==

= Does this require a specific theme? =

Monolith works with any block theme (requires `theme.json`). It is tested with Twenty Twenty-Five.

= Does it break WooCommerce checkout? =

No. Monolith intercepts product navigation only. The MiniCart, cart page, and native checkout work exactly as they normally do.

= Does it work on mobile? =

Yes. On screens narrower than 600px the modal opens full-screen.

= Will it conflict with my payment gateway? =

No. Checkout happens on WooCommerce's native `/checkout` page. Monolith never touches the checkout flow.

= Does it support variable products? =

The quick-view modal links to the product's full page for variable product selection. A future version will support variation selection inside the modal.

== Screenshots ==

1. The single-page store layout with product grid and filters.
2. The quick-view modal open over the product grid.
3. The MiniCart drawer.
4. The Editor block inserter showing "Monolith Store" variation.
5. The Editor Style panel showing Default / Minimal / Fullscreen options.

== Changelog ==

= 1.0.0 =
* Initial release.
* Quick-view modal via WordPress Interactivity API.
* Block Hooks auto-insertion into Product Collection.
* "Monolith Store" block pattern and variation.
* Three modal style variations.
* View Transitions API animation.
* CSS `@starting-style` entry animation.
* Speculation Rules filter for `/product/*`.
* `theme.json` CSS variable theming.
* MiniCart drawer CSS override.
* WordPress Playground blueprint.

== Upgrade Notice ==

= 1.0.0 =
Initial release.
