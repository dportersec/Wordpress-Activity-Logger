<?php
/**
 * White label branding module.
 */

if (!defined('ABSPATH')) {
    exit;
}

function securepress_companion_get_default_white_label_options() {
    return array(
        'enabled'          => 0,
        'admin_footer'     => 'Powered by SecurePress Companion',
        'hide_wp_version'  => 0,
        'dashboard_message'=> 'Welcome to your SecurePress-powered website.',
    );
}

function securepress_companion_get_white_label_options() {
    $saved = get_option('securepress_companion_white_label', array());

    if (!is_array($saved)) {
        $saved = array();
    }

    return wp_parse_args($saved, securepress_companion_get_default_white_label_options());
}

function securepress_companion_sanitize_white_label_options($input) {
    $input = is_array($input) ? $input : array();

    return array(
        'enabled'           => !empty($input['enabled']) ? 1 : 0,
        'admin_footer'      => isset($input['admin_footer']) ? sanitize_text_field($input['admin_footer']) : '',
        'hide_wp_version'   => !empty($input['hide_wp_version']) ? 1 : 0,
        'dashboard_message' => isset($input['dashboard_message']) ? sanitize_textarea_field($input['dashboard_message']) : '',
    );
}

function securepress_companion_register_white_label_settings() {
    register_setting(
        'securepress_companion_white_label_group',
        'securepress_companion_white_label',
        array(
            'type'              => 'array',
            'sanitize_callback' => 'securepress_companion_sanitize_white_label_options',
            'default'           => securepress_companion_get_default_white_label_options(),
        )
    );
}
add_action('admin_init', 'securepress_companion_register_white_label_settings');

function securepress_companion_white_label_enabled() {
    $options = securepress_companion_get_white_label_options();

    return !empty($options['enabled']);
}

function securepress_companion_white_label_admin_footer($text) {
    if (!securepress_companion_white_label_enabled()) {
        return $text;
    }

    $options = securepress_companion_get_white_label_options();

    return esc_html($options['admin_footer']);
}
add_filter('admin_footer_text', 'securepress_companion_white_label_admin_footer');

function securepress_companion_white_label_remove_version($version) {
    if (!securepress_companion_white_label_enabled()) {
        return $version;
    }

    $options = securepress_companion_get_white_label_options();

    return !empty($options['hide_wp_version']) ? '' : $version;
}
add_filter('update_footer', 'securepress_companion_white_label_remove_version', 999);

function securepress_companion_render_white_label_dashboard_widget() {
    if (!securepress_companion_white_label_enabled()) {
        return;
    }

    $options = securepress_companion_get_white_label_options();

    wp_add_dashboard_widget(
        'securepress_companion_white_label_welcome',
        __('Welcome', 'securepress-companion'),
        function() use ($options) {
            ?>
            <p><?php echo esc_html($options['dashboard_message']); ?></p>

            <p>
                <a class="button button-primary" href="<?php echo esc_url(admin_url('admin.php?page=securepress-companion')); ?>">
                    Manage Site Tools
                </a>
            </p>
            <?php
        }
    );
}
add_action('wp_dashboard_setup', 'securepress_companion_render_white_label_dashboard_widget');