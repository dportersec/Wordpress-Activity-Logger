<?php
/**
 * Dashboard widgets module.
 */

if (!defined('ABSPATH')) {
    exit;
}

function securepress_companion_get_default_dashboard_widget_options() {
    return array(
        'enabled' => 1,
    );
}

function securepress_companion_get_dashboard_widget_options() {
    $saved = get_option('securepress_companion_dashboard_widgets', array());

    if (!is_array($saved)) {
        $saved = array();
    }

    return wp_parse_args($saved, securepress_companion_get_default_dashboard_widget_options());
}

function securepress_companion_sanitize_dashboard_widget_options($input) {
    $input = is_array($input) ? $input : array();

    return array(
        'enabled' => !empty($input['enabled']) ? 1 : 0,
    );
}

function securepress_companion_register_dashboard_widget_settings() {
    register_setting(
        'securepress_companion_dashboard_widgets_group',
        'securepress_companion_dashboard_widgets',
        array(
            'type'              => 'array',
            'sanitize_callback' => 'securepress_companion_sanitize_dashboard_widget_options',
            'default'           => securepress_companion_get_default_dashboard_widget_options(),
        )
    );
}
add_action('admin_init', 'securepress_companion_register_dashboard_widget_settings');

function securepress_companion_dashboard_widgets_enabled() {
    $options = securepress_companion_get_dashboard_widget_options();

    return !empty($options['enabled']);
}

function securepress_companion_register_dashboard_widgets() {
    if (!securepress_companion_dashboard_widgets_enabled()) {
        return;
    }

    wp_add_dashboard_widget(
        'securepress_companion_site_status',
        __('SecurePress Site Status', 'securepress-companion'),
        'securepress_companion_render_site_status_widget'
    );

    wp_add_dashboard_widget(
        'securepress_companion_quick_actions',
        __('SecurePress Quick Actions', 'securepress-companion'),
        'securepress_companion_render_quick_actions_widget'
    );
}
add_action('wp_dashboard_setup', 'securepress_companion_register_dashboard_widgets');

function securepress_companion_status_badge($enabled) {
    return $enabled
        ? '<span style="color:#15803d;font-weight:700;">✅ Enabled</span>'
        : '<span style="color:#64748b;font-weight:700;">⬜ Disabled</span>';
}

function securepress_companion_render_site_status_widget() {
    $theme_active        = securepress_companion_is_securepress_theme_active();
    $login_branding      = function_exists('securepress_companion_login_branding_enabled') && securepress_companion_login_branding_enabled();
    $maintenance_enabled = function_exists('securepress_companion_maintenance_enabled') && securepress_companion_maintenance_enabled();
    $security_headers    = function_exists('securepress_companion_security_headers_enabled') && securepress_companion_security_headers_enabled();
    $dashboard_widgets   = securepress_companion_dashboard_widgets_enabled();

    $theme       = wp_get_theme();
    $wp_version  = get_bloginfo('version');
    $php_version = PHP_VERSION;
    ?>

    <div class="securepress-dashboard-widget">
        <p>
            <strong>Quick overview of your SecurePress setup and site environment.</strong>
        </p>

        <table class="widefat striped">
            <tbody>
                <tr>
                    <td><strong>SecurePress Theme</strong></td>
                    <td>
                        <?php echo $theme_active ? '<span style="color:#15803d;font-weight:700;">✅ Active</span>' : '<span style="color:#b45309;font-weight:700;">⚠️ Not Active</span>'; ?>
                    </td>
                </tr>

                <tr>
                    <td><strong>Login Branding</strong></td>
                    <td><?php echo wp_kses_post(securepress_companion_status_badge($login_branding)); ?></td>
                </tr>

                <tr>
                    <td><strong>Maintenance Mode</strong></td>
                    <td><?php echo wp_kses_post(securepress_companion_status_badge($maintenance_enabled)); ?></td>
                </tr>

                <tr>
                    <td><strong>Security Headers</strong></td>
                    <td><?php echo wp_kses_post(securepress_companion_status_badge($security_headers)); ?></td>
                </tr>

                <tr>
                    <td><strong>Dashboard Widgets</strong></td>
                    <td><?php echo wp_kses_post(securepress_companion_status_badge($dashboard_widgets)); ?></td>
                </tr>

                <tr>
                    <td><strong>Active Theme</strong></td>
                    <td><?php echo esc_html($theme->get('Name')); ?></td>
                </tr>

                <tr>
                    <td><strong>WordPress Version</strong></td>
                    <td><?php echo esc_html($wp_version); ?></td>
                </tr>

                <tr>
                    <td><strong>PHP Version</strong></td>
                    <td><?php echo esc_html($php_version); ?></td>
                </tr>
            </tbody>
        </table>

        <p style="margin-top:14px;">
            <a class="button button-primary" href="<?php echo esc_url(admin_url('admin.php?page=securepress-companion')); ?>">
                Open SecurePress Companion
            </a>
        </p>
    </div>

    <?php
}

function securepress_companion_render_quick_actions_widget() {
    ?>

    <div class="securepress-dashboard-widget">
        <p>
            Quick links for common SecurePress setup tasks.
        </p>

        <p>
            <a class="button" href="<?php echo esc_url(admin_url('customize.php')); ?>">
                Open Customizer
            </a>

            <a class="button" href="<?php echo esc_url(admin_url('nav-menus.php')); ?>">
                Manage Menus
            </a>
        </p>

        <p>
            <a class="button" href="<?php echo esc_url(admin_url('admin.php?page=securepress-companion')); ?>">
                Plugin Settings
            </a>

            <a class="button" href="<?php echo esc_url(home_url('/')); ?>" target="_blank" rel="noopener noreferrer">
                View Site
            </a>
        </p>
    </div>

    <?php
}