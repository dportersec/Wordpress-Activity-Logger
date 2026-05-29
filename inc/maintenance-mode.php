<?php
/**
 * Maintenance mode features for SecurePress Companion.
 */

if (!defined('ABSPATH')) {
    exit;
}

function securepress_companion_get_default_maintenance_options() {
    return array(
        'enabled'          => 0,
        'headline'         => 'Site Maintenance',
        'message'          => 'We are currently making improvements. Please check back soon.',
        'button_text'      => 'Contact Us',
        'button_url'       => home_url('/contact'),
        'background_color' => '#070b14',
        'card_color'       => '#0f172a',
        'text_color'       => '#ffffff',
        'muted_color'      => '#94a3b8',
        'accent_color'     => '#38bdf8',
    );
}

function securepress_companion_get_maintenance_options() {
    $saved = get_option('securepress_companion_maintenance_mode', array());

    if (!is_array($saved)) {
        $saved = array();
    }

    return wp_parse_args($saved, securepress_companion_get_default_maintenance_options());
}

function securepress_companion_sanitize_maintenance_options($input) {
    $defaults = securepress_companion_get_default_maintenance_options();
    $input    = is_array($input) ? $input : array();

    return array(
        'enabled'          => !empty($input['enabled']) ? 1 : 0,
        'headline'         => isset($input['headline']) ? sanitize_text_field($input['headline']) : $defaults['headline'],
        'message'          => isset($input['message']) ? sanitize_textarea_field($input['message']) : $defaults['message'],
        'button_text'      => isset($input['button_text']) ? sanitize_text_field($input['button_text']) : $defaults['button_text'],
        'button_url'       => isset($input['button_url']) ? esc_url_raw($input['button_url']) : $defaults['button_url'],
        'background_color' => securepress_companion_sanitize_hex_color($input['background_color'] ?? '', $defaults['background_color']),
        'card_color'       => securepress_companion_sanitize_hex_color($input['card_color'] ?? '', $defaults['card_color']),
        'text_color'       => securepress_companion_sanitize_hex_color($input['text_color'] ?? '', $defaults['text_color']),
        'muted_color'      => securepress_companion_sanitize_hex_color($input['muted_color'] ?? '', $defaults['muted_color']),
        'accent_color'     => securepress_companion_sanitize_hex_color($input['accent_color'] ?? '', $defaults['accent_color']),
    );
}

function securepress_companion_register_maintenance_settings() {
    register_setting(
        'securepress_companion_maintenance_group',
        'securepress_companion_maintenance_mode',
        array(
            'type'              => 'array',
            'sanitize_callback' => 'securepress_companion_sanitize_maintenance_options',
            'default'           => securepress_companion_get_default_maintenance_options(),
        )
    );
}
add_action('admin_init', 'securepress_companion_register_maintenance_settings');

function securepress_companion_maintenance_enabled() {
    $options = securepress_companion_get_maintenance_options();
    return !empty($options['enabled']);
}

function securepress_companion_should_show_maintenance() {
    if (!securepress_companion_maintenance_enabled()) {
        return false;
    }

    if (is_admin() || wp_doing_ajax() || wp_doing_cron()) {
        return false;
    }

    if (current_user_can('manage_options')) {
        return false;
    }

    return true;
}

function securepress_companion_render_maintenance_mode() {
    if (!securepress_companion_should_show_maintenance()) {
        return;
    }

    $options = securepress_companion_get_maintenance_options();

    status_header(503);
    nocache_headers();

    ?>
    <!DOCTYPE html>
    <html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo esc_html($options['headline']); ?></title>
        <style>
            body {
                margin: 0;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 24px;
                background: <?php echo esc_attr($options['background_color']); ?>;
                color: <?php echo esc_attr($options['text_color']); ?>;
                font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            }

            .securepress-maintenance-card {
                width: min(100%, 680px);
                padding: 42px;
                border: 1px solid rgba(148, 163, 184, 0.18);
                border-radius: 24px;
                background: <?php echo esc_attr($options['card_color']); ?>;
                box-shadow: 0 24px 80px rgba(0, 0, 0, 0.35);
                text-align: center;
            }

            .securepress-maintenance-eyebrow {
                display: inline-block;
                margin-bottom: 14px;
                color: <?php echo esc_attr($options['accent_color']); ?>;
                font-size: 0.85rem;
                font-weight: 900;
                text-transform: uppercase;
                letter-spacing: 0.08em;
            }

            .securepress-maintenance-card h1 {
                margin: 0 0 14px;
                font-size: clamp(2.2rem, 6vw, 4rem);
                line-height: 1.05;
            }

            .securepress-maintenance-card p {
                margin: 0 auto 28px;
                max-width: 540px;
                color: <?php echo esc_attr($options['muted_color']); ?>;
                font-size: 1.05rem;
                line-height: 1.7;
            }

            .securepress-maintenance-button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-height: 46px;
                padding: 0 22px;
                border-radius: 999px;
                background: <?php echo esc_attr($options['accent_color']); ?>;
                color: #020617;
                font-weight: 900;
                text-decoration: none;
            }
        </style>
    </head>

    <body>
        <main class="securepress-maintenance-card" role="main">
            <span class="securepress-maintenance-eyebrow">Maintenance Mode</span>

            <h1><?php echo esc_html($options['headline']); ?></h1>

            <p><?php echo esc_html($options['message']); ?></p>

            <?php if (!empty($options['button_text']) && !empty($options['button_url'])) : ?>
                <a class="securepress-maintenance-button" href="<?php echo esc_url($options['button_url']); ?>">
                    <?php echo esc_html($options['button_text']); ?>
                </a>
            <?php endif; ?>
        </main>
    </body>
    </html>
    <?php
    exit;
}
add_action('template_redirect', 'securepress_companion_render_maintenance_mode');