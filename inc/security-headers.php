<?php
/**
 * Security headers module.
 */

if (!defined('ABSPATH')) {
    exit;
}

function securepress_companion_get_default_security_headers_options() {
    return array(
        'enabled'                  => 0,
        'x_frame_options'          => 1,
        'x_content_type_options'   => 1,
        'referrer_policy'          => 1,
        'permissions_policy'       => 1,
    );
}

function securepress_companion_get_security_headers_options() {
    $saved = get_option('securepress_companion_security_headers', array());

    if (!is_array($saved)) {
        $saved = array();
    }

    return wp_parse_args($saved, securepress_companion_get_default_security_headers_options());
}

function securepress_companion_sanitize_security_headers_options($input) {
    $input = is_array($input) ? $input : array();

    return array(
        'enabled'                => !empty($input['enabled']) ? 1 : 0,
        'x_frame_options'        => !empty($input['x_frame_options']) ? 1 : 0,
        'x_content_type_options' => !empty($input['x_content_type_options']) ? 1 : 0,
        'referrer_policy'        => !empty($input['referrer_policy']) ? 1 : 0,
        'permissions_policy'     => !empty($input['permissions_policy']) ? 1 : 0,
    );
}

function securepress_companion_register_security_headers_settings() {
    register_setting(
        'securepress_companion_security_headers_group',
        'securepress_companion_security_headers',
        array(
            'type'              => 'array',
            'sanitize_callback' => 'securepress_companion_sanitize_security_headers_options',
            'default'           => securepress_companion_get_default_security_headers_options(),
        )
    );
}
add_action('admin_init', 'securepress_companion_register_security_headers_settings');

function securepress_companion_security_headers_enabled() {
    $options = securepress_companion_get_security_headers_options();

    return !empty($options['enabled']);
}

function securepress_companion_send_security_headers() {
    if (!securepress_companion_security_headers_enabled()) {
        return;
    }

    if (headers_sent()) {
        return;
    }

    $options = securepress_companion_get_security_headers_options();

    if (!empty($options['x_frame_options'])) {
        header('X-Frame-Options: SAMEORIGIN');
    }

    if (!empty($options['x_content_type_options'])) {
        header('X-Content-Type-Options: nosniff');
    }

    if (!empty($options['referrer_policy'])) {
        header('Referrer-Policy: strict-origin-when-cross-origin');
    }

    if (!empty($options['permissions_policy'])) {
        header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
    }
}
add_action('send_headers', 'securepress_companion_send_security_headers');