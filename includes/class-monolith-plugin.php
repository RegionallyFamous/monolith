<?php
/**
 * Core plugin class.
 *
 * Registers the block, sets up the front-page template, and applies
 * compatibility/performance filters.
 *
 * @package Monolith
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Monolith_Plugin
 */
class Monolith_Plugin {

	/**
	 * Attach all hooks.
	 *
	 * @return void
	 */
	public function run() {
		add_action( 'init', array( $this, 'register_block' ) );
		add_action( 'init', array( $this, 'register_block_template' ) );
		add_action( 'init', array( $this, 'register_block_styles' ) );
		add_filter( 'wp_speculation_rules_href_exclude_paths', array( $this, 'exclude_product_paths_from_speculation' ) );
	}

	/**
	 * Register the quick-view modal block from block.json.
	 *
	 * @return void
	 */
	public function register_block() {
		$block_dir = MONOLITH_PATH . 'build';

		if ( ! file_exists( $block_dir . '/block.json' ) ) {
			return;
		}

		$block = register_block_type( $block_dir );

		if ( $block && isset( $block->view_script_handles ) ) {
			foreach ( $block->view_script_handles as $handle ) {
				wp_set_script_translations( $handle, 'monolith', MONOLITH_PATH . 'languages' );
			}
		}
	}

	/**
	 * Register the fallback front-page block template.
	 *
	 * Requires WordPress 6.7+. Only registers if the file exists.
	 *
	 * @return void
	 */
	public function register_block_template() {
		if ( ! function_exists( 'register_block_template' ) ) {
			return;
		}

		$template_file = MONOLITH_PATH . 'templates/front-page.html';

		if ( ! file_exists( $template_file ) ) {
			return;
		}

		register_block_template(
			'monolith//front-page',
			array(
				'title'       => __( 'Monolith Store', 'monolith' ),
				'description' => __( 'Single-page store layout with product grid, filters, and cart.', 'monolith' ),
				'content'     => file_get_contents( $template_file ), // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			)
		);
	}

	/**
	 * Register editor-side block style variations for the modal.
	 *
	 * @return void
	 */
	public function register_block_styles() {
		register_block_style(
			'monolith/quick-view-modal',
			array(
				'name'       => 'default',
				'label'      => __( 'Default', 'monolith' ),
				'is_default' => true,
			)
		);

		register_block_style(
			'monolith/quick-view-modal',
			array(
				'name'  => 'minimal',
				'label' => __( 'Minimal', 'monolith' ),
			)
		);

		register_block_style(
			'monolith/quick-view-modal',
			array(
				'name'  => 'fullscreen',
				'label' => __( 'Fullscreen', 'monolith' ),
			)
		);
	}

	/**
	 * Exclude /product/* paths from WordPress 6.8+ speculative prerendering.
	 *
	 * Without this, the browser may prerender product pages before a click,
	 * preventing the wc-blocks_viewed_product event from firing correctly.
	 *
	 * @param array $paths Existing excluded paths.
	 * @return array
	 */
	public function exclude_product_paths_from_speculation( $paths ) {
		$paths[] = '/product/*';
		return $paths;
	}
}
