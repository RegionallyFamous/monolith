<?php
/**
 * Plugin Name:       Monolith
 * Plugin URI:        https://github.com/RegionallyFamous/monolith
 * Description:       Transforms a WooCommerce block-theme site into a single-page store. Products, quick-view modal, and cart all on the homepage — no page reloads.
 * Version:           1.0.1
 * Requires at least: 6.8
 * Requires PHP:      8.0
 * Author:            Nick
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       monolith
 * Domain Path:       /languages
 * WC requires at least: 10.4
 * WC tested up to:   10.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MONOLITH_VERSION', '1.0.1' );
define( 'MONOLITH_PATH', plugin_dir_path( __FILE__ ) );
define( 'MONOLITH_URL', plugin_dir_url( __FILE__ ) );

require_once MONOLITH_PATH . 'includes/class-monolith-plugin.php';

/**
 * Initialise the plugin after all plugins are loaded so WooCommerce is available.
 */
add_action(
	'plugins_loaded',
	function () {
		if ( ! class_exists( 'WooCommerce' ) ) {
			add_action(
				'admin_notices',
				function () {
					echo '<div class="error"><p>'
						. esc_html__( 'Monolith requires WooCommerce to be installed and active.', 'monolith' )
						. '</p></div>';
				}
			);
			return;
		}

		( new Monolith_Plugin() )->run();
	}
);

/**
 * Declare compatibility with WooCommerce features.
 * Must run before WooCommerce initialises.
 */
add_action(
	'before_woocommerce_init',
	function () {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', __FILE__, true );
		}
	}
);

register_activation_hook( __FILE__, 'flush_rewrite_rules' );
register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
