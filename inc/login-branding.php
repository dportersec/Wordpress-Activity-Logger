<?php
/**
 * Login branding features for SecurePress Companion.
 */

if (!defined('ABSPATH')) {
    exit;
}

function securepress_companion_get_default_login_branding_options() {
    return array(
        'enabled'          => 0,
        'logo_url'         => '',
        'logo_width'       => 220,
        'background_color' => '#f8fafc',
        'card_color'       => '#ffffff',
        'accent_color'     => '#0f172a',
        'button_color'     => '#0f172a',
        'button_text'      => '#ffffff',
        'login_message'    => '',
        'logo_link'        => home_url('/'),
    );
}

function securepress_companion_get_login_branding_options() {
    $saved = get_option('securepress_companion_login_branding', array());

    if (!is_array($saved)) {
        $saved = array();
    }

    return wp_parse_args($saved, securepress_companion_get_default_login_branding_options());
}

function securepress_companion_sanitize_hex_color($value, $fallback) {
    $value = sanitize_hex_color($value);
    return $value ? $value : $fallback;
}

function securepress_companion_sanitize_login_branding_options($input) {
    $defaults = securepress_companion_get_default_login_branding_options();
    $input    = is_array($input) ? $input : array();

    return array(
        'enabled'          => !empty($input['enabled']) ? 1 : 0,
        'logo_url'         => isset($input['logo_url']) ? esc_url_raw($input['logo_url']) : '',
        'logo_width'       => isset($input['logo_width']) ? min(400, max(80, absint($input['logo_width']))) : $defaults['logo_width'],
        'background_color' => securepress_companion_sanitize_hex_color($input['background_color'] ?? '', $defaults['background_color']),
        'card_color'       => securepress_companion_sanitize_hex_color($input['card_color'] ?? '', $defaults['card_color']),
        'accent_color'     => securepress_companion_sanitize_hex_color($input['accent_color'] ?? '', $defaults['accent_color']),
        'button_color'     => securepress_companion_sanitize_hex_color($input['button_color'] ?? '', $defaults['button_color']),
        'button_text'      => securepress_companion_sanitize_hex_color($input['button_text'] ?? '', $defaults['button_text']),
        'login_message'    => isset($input['login_message']) ? sanitize_text_field($input['login_message']) : '',
        'logo_link'        => isset($input['logo_link']) ? esc_url_raw($input['logo_link']) : home_url('/'),
    );
}

function securepress_companion_register_login_branding_settings() {
    register_setting(
        'securepress_companion_login_branding_group',
        'securepress_companion_login_branding',
        array(
            'type'              => 'array',
            'sanitize_callback' => 'securepress_companion_sanitize_login_branding_options',
            'default'           => securepress_companion_get_default_login_branding_options(),
        )
    );
}
add_action('admin_init', 'securepress_companion_register_login_branding_settings');

function securepress_companion_login_branding_enabled() {
    $options = securepress_companion_get_login_branding_options();
    return !empty($options['enabled']);
}

function securepress_companion_login_branding_styles() {
    if (!securepress_companion_login_branding_enabled()) {
        return;
    }

    $options = securepress_companion_get_login_branding_options();
    $logo    = $options['logo_url'];
    ?>
    <style id="securepress-companion-login-branding">
        body.login {
            min-height: 100vh;
            background: <?php echo esc_attr($options['background_color']); ?>;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        body.login #login {
            width: 380px;
            max-width: calc(100% - 32px);
            padding: 32px 0;
        }

        body.login h1 a {
            <?php if (!empty($logo)) : ?>
            background-image: url('<?php echo esc_url($logo); ?>');
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
            width: <?php echo absint($options['logo_width']); ?>px;
            height: 90px;
            <?php else : ?>
            background-image: none;
            width: auto;
            height: auto;
            text-indent: 0;
            font-size: 1.6rem;
            font-weight: 800;
            color: <?php echo esc_attr($options['accent_color']); ?>;
            <?php endif; ?>
        }

        body.login form {
            border: 1px solid rgba(15, 23, 42, 0.10);
            border-radius: 18px;
            background: <?php echo esc_attr($options['card_color']); ?>;
            box-shadow: 0 20px 55px rgba(15, 23, 42, 0.12);
        }

        body.login label,
        body.login .forgetmenot label,
        body.login #backtoblog a,
        body.login #nav a,
        body.login .privacy-policy-page-link a {
            color: <?php echo esc_attr($options['accent_color']); ?>;
        }

        body.login .button-primary {
            border-color: <?php echo esc_attr($options['button_color']); ?>;
            background: <?php echo esc_attr($options['button_color']); ?>;
            color: <?php echo esc_attr($options['button_text']); ?>;
            font-weight: 700;
        }

        body.login input[type="text"]:focus,
        body.login input[type="password"]:focus,
        body.login input[type="checkbox"]:focus {
            border-color: <?php echo esc_attr($options['button_color']); ?>;
            box-shadow: 0 0 0 1px <?php echo esc_attr($options['button_color']); ?>;
        }

        .securepress-login-message {
            margin: 0 0 18px;
            padding: 12px 14px;
            border-left: 4px solid <?php echo esc_attr($options['button_color']); ?>;
            border-radius: 10px;
            background: <?php echo esc_attr($options['card_color']); ?>;
            color: <?php echo esc_attr($options['accent_color']); ?>;
            box-shadow: 0 10px 35px rgba(15, 23, 42, 0.08);
        }
    </style>
    <?php
}
add_action('login_enqueue_scripts', 'securepress_companion_login_branding_styles');

function securepress_companion_login_logo_url() {
    if (!securepress_companion_login_branding_enabled()) {
        return home_url('/');
    }

    $options = securepress_companion_get_login_branding_options();
    return !empty($options['logo_link']) ? $options['logo_link'] : home_url('/');
}
add_filter('login_headerurl', 'securepress_companion_login_logo_url');

function securepress_companion_login_logo_title() {
    return get_bloginfo('name');
}
add_filter('login_headertext', 'securepress_companion_login_logo_title');

function securepress_companion_login_message($message) {
    if (!securepress_companion_login_branding_enabled()) {
        return $message;
    }

    $options = securepress_companion_get_login_branding_options();

    if (empty($options['login_message'])) {
        return $message;
    }

    return '<p class="securepress-login-message">' . esc_html($options['login_message']) . '</p>' . $message;
}
add_filter('login_message', 'securepress_companion_login_message');
