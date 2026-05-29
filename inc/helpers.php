<?php
/**
 * Helper functions for SecurePress Companion.
 */

if (!defined('ABSPATH')) {
    exit;
}

function securepress_companion_is_securepress_theme_active() {
    $theme = wp_get_theme();

    return $theme && (
        $theme->get('TextDomain') === 'securepress-agency' ||
        $theme->get('Name') === 'SecurePress Agency'
    );
}