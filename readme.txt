=== Menu Sync for Navigation Block ===
Contributors: weblazer35
Tags: navigation, menu, blocks, gutenberg, sync
Requires at least: 6.0
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Automatically synchronizes Navigation blocks with classic WordPress menus using the native import system.

== Description ==

Menu Sync for Navigation Block bridges the gap between classic WordPress menus and the new Navigation block system. This plugin allows you to:

* **Sync Navigation blocks with classic menus** - Keep your navigation blocks updated with your classic menu changes
* **Auto-sync functionality** - Automatically update navigation blocks when classic menus are modified
* **Manual sync option** - Sync on-demand when needed
* **Native WordPress integration** - Uses WordPress's built-in menu converter for seamless compatibility
* **Block Editor integration** - Easy-to-use controls directly in the Navigation block sidebar

Perfect for sites transitioning from classic themes to block themes, or for developers who want to maintain both classic and block-based navigation systems.

== Key Features ==

* **Zero configuration** - Works out of the box
* **Performance optimized** - Uses WordPress native functions
* **Developer friendly** - Clean, well-documented code
* **Translation ready** - Full internationalization support
* **Secure** - Follows WordPress security best practices

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/menu-sync-for-navigation-block` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Edit any Navigation block in the Block Editor.
4. In the Navigation block sidebar, find the "Auto Sync with Classic Menu" panel.
5. Select a classic menu to sync with and configure your sync preferences.

== Frequently Asked Questions ==

= Does this work with all themes? =

Yes, this plugin works with any theme that supports the Navigation block (WordPress 5.9+).

= Will this affect my existing menus? =

No, this plugin only reads from classic menus and updates Navigation blocks. Your classic menus remain unchanged.

= Can I sync multiple Navigation blocks with the same menu? =

Yes, you can link multiple Navigation blocks to the same classic menu.

= What happens if I delete a classic menu? =

The Navigation block will retain its last synced content. You can manually update it or link it to a different menu.

= Is this compatible with menu plugins? =

Yes, as long as the menu plugins work with WordPress's standard menu system.

== Screenshots ==

1. Navigation block sidebar showing Menu Synchronization panel
2. Menu selection dropdown with sync options including "Do not sync" and available classic menus

== Changelog ==

= 1.0.0 =
* Initial release
* Navigation block to classic menu synchronization
* Auto-sync functionality
* Manual sync option
* Block Editor integration
* REST API endpoints for sync operations

== Upgrade Notice ==

= 1.0.0 =
Initial release of Menu Sync for Navigation Block.

== Developer Notes ==

This plugin uses WordPress's native `WP_Classic_To_Block_Menu_Converter` class to ensure maximum compatibility and future-proofing.

For developers: The plugin exposes REST API endpoints for programmatic access:
* `GET/POST /wp-json/menu-sync-for-navigation-block/v1/settings/{post_id}` - Manage sync settings
* `POST /wp-json/menu-sync-for-navigation-block/v1/sync/{post_id}/{menu_id}` - Trigger sync operation

== Support ==

For support, feature requests, or bug reports, please visit: https://weblazer.fr
