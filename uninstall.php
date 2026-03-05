<?php
/**
 * Plugin uninstall handler.
 *
 * Runs when the user deletes the plugin from the WordPress admin.
 * Removes all options stored by Monolith.
 *
 * @package Monolith
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'monolith_settings' );
delete_option( 'monolith_version' );

// Remove any transients we may have set.
delete_transient( 'monolith_products_cache' );
