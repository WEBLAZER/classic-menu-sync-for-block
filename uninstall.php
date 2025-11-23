<?php
/**
 * Uninstall Menu Sync for Navigation Block
 *
 * @package Menu_Sync_For_Navigation_Block
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/**
 * Clean up plugin data on uninstall.
 */
function menu_sync_for_navigation_block_uninstall() {
	// Remove all plugin meta data from wp_navigation posts
	delete_post_meta_by_key( '_nas_linked_menu_id' );
	delete_post_meta_by_key( '_nas_auto_sync_enabled' );
	delete_post_meta_by_key( '_nas_last_sync' );

	// Clean up any transients (if we had any)
	delete_transient( 'menu_sync_for_navigation_block_cache' );

	// Remove any options (if we had any)
	delete_option( 'menu_sync_for_navigation_block_version' );
	delete_option( 'menu_sync_for_navigation_block_settings' );

	// Clear any cached data
	wp_cache_flush();
}

// Run the uninstall function
menu_sync_for_navigation_block_uninstall();
