<?php
/**
 * Cleanup on uninstall.
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

delete_option('securepress_companion_version');
delete_option('securepress_companion_login_branding');
delete_option('securepress_companion_activity_logs');
