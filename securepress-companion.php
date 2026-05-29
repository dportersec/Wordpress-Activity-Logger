<?php
/**
 * Plugin Name: SecurePress Companion
 * Plugin URI: https://example.com/securepress-companion
 * Description: Companion plugin for the SecurePress Agency theme, adding client-focused tools such as login branding, maintenance mode, security helpers, and admin enhancements.
 * Version: 1.0.0
 * Author: Dillon Porter
 * Text Domain: securepress-companion
 */

if (!defined('ABSPATH')) {
    exit;
}

define('SECUREPRESS_COMPANION_VERSION', '1.0.0');
define('SECUREPRESS_COMPANION_FILE', __FILE__);
define('SECUREPRESS_COMPANION_PATH', plugin_dir_path(__FILE__));
define('SECUREPRESS_COMPANION_URL', plugin_dir_url(__FILE__));

require_once SECUREPRESS_COMPANION_PATH . 'inc/helpers.php';
require_once SECUREPRESS_COMPANION_PATH . 'inc/login-branding.php';
require_once SECUREPRESS_COMPANION_PATH . 'inc/maintenance-mode.php';
require_once SECUREPRESS_COMPANION_PATH . 'inc/security-headers.php';
require_once SECUREPRESS_COMPANION_PATH . 'inc/dashboard-widgets.php';
require_once SECUREPRESS_COMPANION_PATH . 'inc/white-label.php';
require_once SECUREPRESS_COMPANION_PATH . 'inc/activity-logger.php';
require_once SECUREPRESS_COMPANION_PATH . 'admin/admin-page.php';

function securepress_companion_activate() {
    add_option('securepress_companion_version', SECUREPRESS_COMPANION_VERSION);
    add_option('securepress_companion_login_branding', securepress_companion_get_default_login_branding_options());
}
register_activation_hook(__FILE__, 'securepress_companion_activate');

function securepress_companion_deactivate() {
    // Reserved for future cleanup.
}
register_deactivation_hook(__FILE__, 'securepress_companion_deactivate');
