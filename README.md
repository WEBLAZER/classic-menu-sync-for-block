# Classic Menu Sync for Block

[![GitHub](https://img.shields.io/badge/GitHub-Repository-blue?logo=github)](https://github.com/WEBLAZER/classic-menu-sync-for-block)

A WordPress plugin that automatically synchronizes Navigation blocks with classic menus using WordPress native import system.

## Description

Classic Menu Sync for Block bridges the gap between classic WordPress menus and the new Navigation block system. This plugin allows you to:

* **Sync Navigation blocks with classic menus** - Keep your Navigation blocks updated with your classic menu changes
* **Auto-sync functionality** - Automatically update Navigation blocks when classic menus are modified
* **Manual sync option** - Sync on-demand when needed
* **Native WordPress integration** - Uses WordPress's built-in menu converter for seamless compatibility
* **Block Editor integration** - Easy-to-use controls directly in the Navigation block sidebar

Perfect for sites transitioning from classic themes to block themes, or for developers who want to maintain both classic and block-based navigation systems.

## Key Features

* **Zero configuration** - Works out of the box
* **Performance optimized** - Uses WordPress native functions
* **Developer friendly** - Clean, well-documented code
* **Translation ready** - Full internationalization support
* **Secure** - Follows WordPress security best practices

## Installation

1. Download the plugin or clone this repository into `/wp-content/plugins/classic-menu-sync-for-block`
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Edit any Navigation block in the Block Editor
4. In the Navigation block sidebar, find the "Auto Sync with Classic Menu" panel
5. Select a classic menu to sync with and configure your sync preferences

## Requirements

* WordPress 6.0 or higher
* PHP 7.4 or higher
* Theme compatible with Navigation blocks (WordPress 5.9+)

## Usage

### Automatic synchronization

1. Edit a Navigation block in the Block Editor
2. In the sidebar, find the "Menu Synchronization" panel
3. Select a classic menu from the dropdown
4. Enable automatic synchronization if you want the block to update automatically when the classic menu is modified
5. Click "Sync Now" for immediate synchronization

### Manual synchronization

You can trigger a manual synchronization at any time by clicking the "Sync Now" button in the synchronization panel.

## REST API

The plugin exposes REST API endpoints for programmatic access:

* `GET/POST /wp-json/classic-menu-sync-for-block/v1/settings/{post_id}` - Manage sync settings
* `POST /wp-json/classic-menu-sync-for-block/v1/sync/{post_id}/{menu_id}` - Trigger sync operation

## Development

This plugin uses WordPress's native `WP_Classic_To_Block_Menu_Converter` class to ensure maximum compatibility and future-proofing.

### Code structure

```
classic-menu-sync-for-block/
├── assets/
│   ├── editor.js      # Block editor scripts
│   └── editor.css     # Block editor styles
├── languages/         # Translation files
├── classic-menu-sync-for-block.php  # Main file
├── uninstall.php      # Uninstall script
└── readme.txt         # Readme file for WordPress.org
```

## FAQ

**Does this work with all themes?**

Yes, this plugin works with any theme that supports the Navigation block (WordPress 5.9+).

**Will this affect my existing menus?**

No, this plugin only reads from classic menus and updates Navigation blocks. Your classic menus remain unchanged.

**Can I sync multiple Navigation blocks with the same menu?**

Yes, you can link multiple Navigation blocks to the same classic menu.

**What happens if I delete a classic menu?**

The Navigation block will retain its last synced content. You can manually update it or link it to a different menu.

## GitHub Repository

This plugin is available on GitHub: [https://github.com/WEBLAZER/classic-menu-sync-for-block](https://github.com/WEBLAZER/classic-menu-sync-for-block)

You can contribute, report issues, or download the latest version from the repository.

## Support

For support, feature requests, or bug reports, visit: https://weblazer.fr

## License

GPL v2 or later - https://www.gnu.org/licenses/gpl-2.0.html

## Author

**weblazer** - [WordPress.org Profile](https://profiles.wordpress.org/weblazer/) | [weblazer.fr](https://weblazer.fr)

## Changelog

### 1.0.1
* Updated tested up to WordPress 6.9
* Translated all plugin files to English
* Code improvements and documentation updates

### 1.0.0
* Initial release
* Navigation block to classic menu synchronization
* Auto-sync functionality
* Manual sync option
* Block Editor integration
* REST API endpoints for sync operations

