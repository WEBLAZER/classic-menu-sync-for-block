<?php
/**
 * Plugin Name: Classic Menu Sync for Block
 * Plugin URI: https://github.com/WEBLAZER/classic-menu-sync-for-block
 * GitHub Plugin URI: https://github.com/WEBLAZER/classic-menu-sync-for-block
 * Description: Automatically synchronizes Navigation blocks with classic menus using WordPress native import system.
 * Version: 1.0.1
 * Author: weblazer
 * Author URI: https://profiles.wordpress.org/weblazer/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: classic-menu-sync-for-block
 * Domain Path: /languages
 * Requires at least: 6.0
 * Tested up to: 6.9
 * Requires PHP: 7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Classic_Menu_Sync_For_Block {

	/**
	 * Initialize the plugin.
	 */
	public static function init() {
		add_action( 'enqueue_block_editor_assets', array( __CLASS__, 'enqueue_editor_assets' ) );
		add_action( 'wp_update_nav_menu', array( __CLASS__, 'sync_navigation_blocks_on_menu_save' ), 10, 1 );
		add_action( 'rest_api_init', array( __CLASS__, 'register_rest_routes' ) );
		add_action( 'init', array( __CLASS__, 'register_meta_fields' ) );
	}

	/**
	 * Enqueue editor assets.
	 */
	public static function enqueue_editor_assets() {
		wp_enqueue_script(
			'classic-menu-sync-for-block',
			plugins_url( 'assets/editor.js', __FILE__ ),
			array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-i18n', 'wp-block-editor', 'wp-compose', 'wp-hooks', 'wp-api-fetch' ),
			'1.0.1',
			true
		);

		wp_enqueue_style(
			'classic-menu-sync-for-block',
			plugins_url( 'assets/editor.css', __FILE__ ),
			array(),
			'1.0.1'
		);

		// Pass available menus to JavaScript
		$menus = wp_get_nav_menus();
		$menu_options = array();
		foreach ( $menus as $menu ) {
			$menu_options[] = array(
				'label' => $menu->name,
				'value' => $menu->term_id,
			);
		}

		wp_localize_script(
			'classic-menu-sync-for-block',
			'classicMenuSyncForBlock',
			array(
				'menus' => $menu_options,
				'nonce' => wp_create_nonce( 'classic_menu_sync_for_block' ),
			)
		);
	}

	/**
	 * Register REST API routes.
	 */
	public static function register_rest_routes() {
		register_rest_route(
			'classic-menu-sync-for-block/v1',
			'/sync/(?P<post_id>\d+)/(?P<menu_id>\d+)',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( __CLASS__, 'rest_sync_navigation' ),
				'permission_callback' => function () {
					return current_user_can( 'edit_theme_options' );
				},
				'args'                => array(
					'post_id' => array(
						'type'              => 'integer',
						'required'          => true,
						'sanitize_callback' => 'absint',
					),
					'menu_id' => array(
						'type'              => 'integer',
						'required'          => true,
						'sanitize_callback' => 'absint',
					),
				),
			)
		);

		register_rest_route(
			'classic-menu-sync-for-block/v1',
			'/settings/(?P<post_id>\d+)',
			array(
				'methods'             => array( WP_REST_Server::READABLE, WP_REST_Server::CREATABLE ),
				'callback'            => array( __CLASS__, 'rest_handle_settings' ),
				'permission_callback' => function () {
					return current_user_can( 'edit_theme_options' );
				},
				'args'                => array(
					'post_id' => array(
						'type'              => 'integer',
						'required'          => true,
						'sanitize_callback' => 'absint',
					),
					'linked_menu_id' => array(
						'type'              => 'integer',
						'sanitize_callback' => 'absint',
					),
					'auto_sync_enabled' => array(
						'type' => 'boolean',
					),
				),
			)
		);
	}

	/**
	 * REST callback to sync a navigation block with a classic menu.
	 */
	public static function rest_sync_navigation( WP_REST_Request $request ) {
		$post_id = $request->get_param( 'post_id' );
		$menu_id = $request->get_param( 'menu_id' );

		$result = self::sync_navigation_with_classic_menu( $post_id, $menu_id );

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		return rest_ensure_response( array( 'success' => true ) );
	}

	/**
	 * REST callback to handle navigation settings (GET and POST).
	 */
	public static function rest_handle_settings( WP_REST_Request $request ) {
		$post_id = $request->get_param( 'post_id' );

		if ( $request->get_method() === 'POST' ) {
			// Save settings
			$linked_menu_id = $request->get_param( 'linked_menu_id' );
			$auto_sync_enabled = $request->get_param( 'auto_sync_enabled' );

			if ( $linked_menu_id !== null ) {
				if ( $linked_menu_id ) {
					update_post_meta( $post_id, '_nas_linked_menu_id', $linked_menu_id );
				} else {
					// Remove the link if menu ID is null/empty (disable sync)
					delete_post_meta( $post_id, '_nas_linked_menu_id' );
				}
			}

			if ( $auto_sync_enabled !== null ) {
				update_post_meta( $post_id, '_nas_auto_sync_enabled', $auto_sync_enabled ? '1' : '0' );
			}
		}

		// Return current settings
		$linked_menu_id = get_post_meta( $post_id, '_nas_linked_menu_id', true );
		$auto_sync_enabled = get_post_meta( $post_id, '_nas_auto_sync_enabled', true );
		$last_sync = get_post_meta( $post_id, '_nas_last_sync', true );

		return rest_ensure_response( array(
			'linked_menu_id' => $linked_menu_id ? intval( $linked_menu_id ) : null,
			'auto_sync_enabled' => $auto_sync_enabled === '1',
			'last_sync' => $last_sync ? intval( $last_sync ) : null,
		) );
	}

	/**
	 * Sync navigation blocks when a classic menu is saved.
	 */
	public static function sync_navigation_blocks_on_menu_save( $menu_id ) {
		// Check cache first to avoid repeated queries
		$cache_key = 'classic_menu_sync_nav_posts_' . $menu_id;
		$linked_navigations = wp_cache_get( $cache_key, 'classic_menu_sync_for_block' );
		
		if ( false === $linked_navigations ) {
			// Use WP_Query for better performance control and WordPress compliance
			$query = new WP_Query( array(
				'post_type'      => 'wp_navigation',
				'post_status'    => 'publish',
				'meta_query'     => array(
					array(
						'key'     => '_nas_linked_menu_id',
						'value'   => $menu_id,
						'compare' => '=',
					),
				),
				'fields'         => 'ids',
				'posts_per_page' => 100, // Reasonable limit instead of -1
				'no_found_rows'  => true, // Skip counting for performance
				'update_post_meta_cache' => false, // Skip meta cache
				'update_post_term_cache' => false, // Skip term cache
			) );
			
			$linked_navigations = $query->posts;
			
			// Cache for 5 minutes
			wp_cache_set( $cache_key, $linked_navigations, 'classic_menu_sync_for_block', 300 );
		}

		foreach ( $linked_navigations as $navigation_id ) {
			// Clear cache when syncing to ensure fresh data
			wp_cache_delete( 'classic_menu_sync_nav_posts_' . $menu_id, 'classic_menu_sync_for_block' );
			self::sync_navigation_with_classic_menu( (int) $navigation_id, $menu_id );
		}
	}

	/**
	 * Sync a navigation post with a classic menu using WordPress native converter.
	 */
	public static function sync_navigation_with_classic_menu( $post_id, $menu_id ) {
		if ( ! class_exists( 'WP_Classic_To_Block_Menu_Converter' ) ) {
			return new WP_Error( 'converter_not_available', __( 'Menu converter not available.', 'classic-menu-sync-for-block' ) );
		}

		$menu = wp_get_nav_menu_object( $menu_id );
		if ( ! $menu ) {
			return new WP_Error( 'invalid_menu', __( 'Menu not found.', 'classic-menu-sync-for-block' ) );
		}

		// Convert classic menu to blocks using WordPress native converter
		$blocks_content = WP_Classic_To_Block_Menu_Converter::convert( $menu );

		if ( is_wp_error( $blocks_content ) ) {
			return $blocks_content;
		}

		// Update the navigation post
		$result = wp_update_post( array(
			'ID'           => $post_id,
			'post_content' => $blocks_content,
		) );

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		// Store the link between navigation and menu (only if not already set)
		if ( ! get_post_meta( $post_id, '_nas_linked_menu_id', true ) ) {
			update_post_meta( $post_id, '_nas_linked_menu_id', $menu_id );
		}
		update_post_meta( $post_id, '_nas_last_sync', time() );

		return true;
	}

	/**
	 * Get the linked menu ID for a navigation post.
	 */
	public static function get_linked_menu_id( $post_id ) {
		return get_post_meta( $post_id, '_nas_linked_menu_id', true );
	}

	/**
	 * Check if auto-sync is enabled for a navigation post.
	 */
	public static function is_auto_sync_enabled( $post_id ) {
		return get_post_meta( $post_id, '_nas_auto_sync_enabled', true ) === '1';
	}

	/**
	 * Register meta fields for REST API access.
	 */
	public static function register_meta_fields() {
		register_post_meta( 'wp_navigation', '_nas_linked_menu_id', array(
			'type'         => 'integer',
			'single'       => true,
			'show_in_rest' => true,
		) );

		register_post_meta( 'wp_navigation', '_nas_auto_sync_enabled', array(
			'type'         => 'string',
			'single'       => true,
			'show_in_rest' => true,
		) );

		register_post_meta( 'wp_navigation', '_nas_last_sync', array(
			'type'         => 'integer',
			'single'       => true,
			'show_in_rest' => true,
		) );
	}
}

// Initialize the plugin
Classic_Menu_Sync_For_Block::init();