<?php
/**
 * SecurePress Companion admin page.
 */

if (!defined('ABSPATH')) {
    exit;
}

function securepress_companion_admin_menu() {
    add_menu_page(
        __('SecurePress Companion', 'securepress-companion'),
        __('SecurePress', 'securepress-companion'),
        'manage_options',
        'securepress-companion',
        'securepress_companion_render_admin_page',
        'dashicons-shield-alt',
        58
    );
}
add_action('admin_menu', 'securepress_companion_admin_menu');

function securepress_companion_admin_assets($hook) {
    if ($hook !== 'toplevel_page_securepress-companion') {
        return;
    }

    wp_enqueue_media();

    wp_enqueue_style(
        'securepress-companion-admin',
        SECUREPRESS_COMPANION_URL . 'assets/css/admin.css',
        array(),
        SECUREPRESS_COMPANION_VERSION
    );

    wp_enqueue_script(
        'securepress-companion-admin',
        SECUREPRESS_COMPANION_URL . 'assets/js/admin.js',
        array('jquery'),
        SECUREPRESS_COMPANION_VERSION,
        true
    );
}
add_action('admin_enqueue_scripts', 'securepress_companion_admin_assets');

function securepress_companion_render_login_field($name, $label, $type = 'text', $description = '') {
    $options = securepress_companion_get_login_branding_options();
    $id      = 'securepress_login_' . $name;
    $value   = $options[$name] ?? '';
    ?>
    <div class="securepress-companion-field">
        <label for="<?php echo esc_attr($id); ?>"><?php echo esc_html($label); ?></label>
        <input
            id="<?php echo esc_attr($id); ?>"
            type="<?php echo esc_attr($type); ?>"
            name="securepress_companion_login_branding[<?php echo esc_attr($name); ?>]"
            value="<?php echo esc_attr($value); ?>"
            <?php echo $type === 'number' ? 'min="80" max="400" step="10"' : ''; ?>
        />
        <?php if (!empty($description)) : ?>
            <p class="description"><?php echo esc_html($description); ?></p>
        <?php endif; ?>
    </div>
    <?php
}

function securepress_companion_render_maintenance_field($name, $label, $type = 'text', $description = '') {
    $options = securepress_companion_get_maintenance_options();
    $id      = 'securepress_maintenance_' . $name;
    $value   = $options[$name] ?? '';
    ?>
    <div class="securepress-companion-field">
        <label for="<?php echo esc_attr($id); ?>"><?php echo esc_html($label); ?></label>

        <?php if ($type === 'textarea') : ?>
            <textarea
                id="<?php echo esc_attr($id); ?>"
                name="securepress_companion_maintenance_mode[<?php echo esc_attr($name); ?>]"
                rows="4"
            ><?php echo esc_textarea($value); ?></textarea>
        <?php else : ?>
            <input
                id="<?php echo esc_attr($id); ?>"
                type="<?php echo esc_attr($type); ?>"
                name="securepress_companion_maintenance_mode[<?php echo esc_attr($name); ?>]"
                value="<?php echo esc_attr($value); ?>"
            />
        <?php endif; ?>

        <?php if (!empty($description)) : ?>
            <p class="description"><?php echo esc_html($description); ?></p>
        <?php endif; ?>
    </div>
    <?php
}

function securepress_companion_render_admin_page() {
    $login_options       = securepress_companion_get_login_branding_options();
    $maintenance_options = securepress_companion_get_maintenance_options();
    $security_options    = securepress_companion_get_security_headers_options();
    $white_label_options = securepress_companion_get_white_label_options();
    ?>
    <div class="wrap securepress-companion-admin">
        <h1>SecurePress Companion</h1>

        <p class="securepress-companion-intro">
            Client-focused WordPress tools built to extend the SecurePress Agency theme.
        </p>

        <?php if (!securepress_companion_is_securepress_theme_active()) : ?>
            <div class="notice notice-warning inline">
                <p><strong>Heads up:</strong> SecurePress Agency does not appear to be the active theme. The plugin will still work, but it was designed to pair with that theme.</p>
            </div>
        <?php endif; ?>

        <div class="securepress-companion-layout">

            <div>
                <form method="post" action="options.php" class="securepress-companion-card securepress-companion-settings-card">
                    <?php settings_fields('securepress_companion_login_branding_group'); ?>

                    <div class="securepress-companion-card-header">
                        <div>
                            <h2>Login Branding</h2>
                            <p>Customize the WordPress login screen with client branding.</p>
                        </div>
                        <span class="securepress-companion-badge securepress-companion-badge-live">Active Feature</span>
                    </div>

                    <label class="securepress-companion-toggle">
                        <input type="checkbox" name="securepress_companion_login_branding[enabled]" value="1" <?php checked($login_options['enabled'], 1); ?> />
                        <span>Enable custom login branding</span>
                    </label>

                    <div class="securepress-companion-field">
                        <label for="securepress_login_logo_url">Logo URL</label>
                        <div class="securepress-companion-media-row">
                            <input
                                id="securepress_login_logo_url"
                                type="url"
                                name="securepress_companion_login_branding[logo_url]"
                                value="<?php echo esc_attr($login_options['logo_url']); ?>"
                                placeholder="https://example.com/logo.png"
                            />
                            <button type="button" class="button securepress-upload-logo">Choose Logo</button>
                        </div>
                    </div>

                    <?php
                    securepress_companion_render_login_field('logo_width', 'Logo Width', 'number', 'Allowed range: 80px to 400px.');
                    securepress_companion_render_login_field('logo_link', 'Logo Link', 'url', 'Where the logo sends users when clicked.');
                    securepress_companion_render_login_field('login_message', 'Login Message', 'text', 'Optional short message shown above the login form.');
                    ?>

                    <div class="securepress-companion-color-grid">
                        <?php
                        securepress_companion_render_login_field('background_color', 'Background Color', 'color');
                        securepress_companion_render_login_field('card_color', 'Login Card Color', 'color');
                        securepress_companion_render_login_field('accent_color', 'Text/Link Color', 'color');
                        securepress_companion_render_login_field('button_color', 'Button Color', 'color');
                        securepress_companion_render_login_field('button_text', 'Button Text Color', 'color');
                        ?>
                    </div>

                    <?php submit_button(__('Save Login Branding', 'securepress-companion')); ?>
                </form>

                <form method="post" action="options.php" class="securepress-companion-card securepress-companion-settings-card">
                    <?php settings_fields('securepress_companion_maintenance_group'); ?>

                    <div class="securepress-companion-card-header">
                        <div>
                            <h2>Maintenance Mode</h2>
                            <p>Display a branded maintenance page while work is in progress.</p>
                        </div>
                        <span class="securepress-companion-badge securepress-companion-badge-live">Active Feature</span>
                    </div>

                    <label class="securepress-companion-toggle">
                        <input type="checkbox" name="securepress_companion_maintenance_mode[enabled]" value="1" <?php checked($maintenance_options['enabled'], 1); ?> />
                        <span>Enable maintenance mode</span>
                    </label>

                    <?php
                    securepress_companion_render_maintenance_field('headline', 'Headline', 'text');
                    securepress_companion_render_maintenance_field('message', 'Message', 'textarea');
                    securepress_companion_render_maintenance_field('button_text', 'Button Text', 'text');
                    securepress_companion_render_maintenance_field('button_url', 'Button URL', 'url');
                    ?>

                    <div class="securepress-companion-color-grid">
                        <?php
                        securepress_companion_render_maintenance_field('background_color', 'Background Color', 'color');
                        securepress_companion_render_maintenance_field('card_color', 'Card Color', 'color');
                        securepress_companion_render_maintenance_field('text_color', 'Text Color', 'color');
                        securepress_companion_render_maintenance_field('muted_color', 'Muted Text Color', 'color');
                        securepress_companion_render_maintenance_field('accent_color', 'Accent Color', 'color');
                        ?>
                    </div>

                    <?php submit_button(__('Save Maintenance Mode', 'securepress-companion')); ?>
                </form>

                <form method="post" action="options.php" class="securepress-companion-card securepress-companion-settings-card">
                    <?php settings_fields('securepress_companion_security_headers_group'); ?>

                    <div class="securepress-companion-card-header">
                        <div>
                            <h2>Security Headers</h2>
                            <p>Add practical HTTP security headers for improved baseline hardening.</p>
                        </div>
                        <span class="securepress-companion-badge securepress-companion-badge-live">Active Feature</span>
                    </div>

                    <label class="securepress-companion-toggle">
                        <input type="checkbox" name="securepress_companion_security_headers[enabled]" value="1" <?php checked($security_options['enabled'], 1); ?> />
                        <span>Enable security headers</span>
                    </label>

                    <label class="securepress-companion-toggle">
                        <input type="checkbox" name="securepress_companion_security_headers[x_frame_options]" value="1" <?php checked($security_options['x_frame_options'], 1); ?> />
                        <span>X-Frame-Options: SAMEORIGIN</span>
                    </label>

                    <label class="securepress-companion-toggle">
                        <input type="checkbox" name="securepress_companion_security_headers[x_content_type_options]" value="1" <?php checked($security_options['x_content_type_options'], 1); ?> />
                        <span>X-Content-Type-Options: nosniff</span>
                    </label>

                    <label class="securepress-companion-toggle">
                        <input type="checkbox" name="securepress_companion_security_headers[referrer_policy]" value="1" <?php checked($security_options['referrer_policy'], 1); ?> />
                        <span>Referrer-Policy: strict-origin-when-cross-origin</span>
                    </label>

                    <label class="securepress-companion-toggle">
                        <input type="checkbox" name="securepress_companion_security_headers[permissions_policy]" value="1" <?php checked($security_options['permissions_policy'], 1); ?> />
                        <span>Permissions-Policy: camera=(), microphone=(), geolocation=()</span>
                    </label>

                    <?php submit_button(__('Save Security Headers', 'securepress-companion')); ?>
                </form>
            </div>

            <form method="post" action="options.php" class="securepress-companion-card securepress-companion-settings-card">

    <?php settings_fields('securepress_companion_white_label_group'); ?>

    <div class="securepress-companion-card-header">
        <div>
            <h2>White Label Branding</h2>
            <p>Customize the WordPress admin experience for clients.</p>
        </div>

        <span class="securepress-companion-badge securepress-companion-badge-live">
            Active Feature
        </span>
    </div>

    <label class="securepress-companion-toggle">
        <input
            type="checkbox"
            name="securepress_companion_white_label[enabled]"
            value="1"
            <?php checked($white_label_options['enabled'], 1); ?>
        />
        <span>Enable White Label Branding</span>
    </label>

    <div class="securepress-companion-field">
        <label>Admin Footer Text</label>

        <input
            type="text"
            name="securepress_companion_white_label[admin_footer]"
            value="<?php echo esc_attr($white_label_options['admin_footer']); ?>"
        />

        <p class="description">
            Replaces the default WordPress footer text.
        </p>
    </div>

    <div class="securepress-companion-field">
        <label>Dashboard Welcome Message</label>

        <textarea
            name="securepress_companion_white_label[dashboard_message]"
            rows="4"
        ><?php echo esc_textarea($white_label_options['dashboard_message']); ?></textarea>
    </div>

    <label class="securepress-companion-toggle">
        <input
            type="checkbox"
            name="securepress_companion_white_label[hide_wp_version]"
            value="1"
            <?php checked($white_label_options['hide_wp_version'], 1); ?>
        />
        <span>Hide WordPress Version</span>
    </label>

    <?php submit_button(__('Save White Label Branding', 'securepress-companion')); ?>

</form>

            <div class="securepress-companion-side">
                <div class="securepress-companion-card">
                    <h2>Testing Guide</h2>
                    <p>Test login branding and maintenance mode in an incognito window.</p>
                    <p><code><?php echo esc_html(wp_login_url()); ?></code></p>
                    <a class="button button-secondary" href="<?php echo esc_url(wp_login_url()); ?>" target="_blank" rel="noopener noreferrer">Open Login Page</a>
                </div>

                <div class="securepress-companion-card">
                    <h2>Security Header Testing</h2>
                    <p>After enabling headers, inspect your site’s response headers in DevTools → Network.</p>
                    <p><code><?php echo esc_html(home_url('/')); ?></code></p>
                    <a class="button button-secondary" href="<?php echo esc_url(home_url('/')); ?>" target="_blank" rel="noopener noreferrer">Open Site</a>
                </div>

               <form method="post" action="options.php" class="securepress-companion-card">
    <?php settings_fields('securepress_companion_dashboard_widgets_group'); ?>
    <?php $dashboard_options = securepress_companion_get_dashboard_widget_options(); ?>

    <div class="securepress-companion-card-header">
        <div>
            <h2>Dashboard Widgets</h2>
            <p>Add useful site status and quick-action widgets to WordPress admin.</p>
        </div>

        <span class="securepress-companion-badge securepress-companion-badge-live">
            Active Feature
        </span>
    </div>

    <label class="securepress-companion-toggle">
        <input
            type="checkbox"
            name="securepress_companion_dashboard_widgets[enabled]"
            value="1"
            <?php checked($dashboard_options['enabled'], 1); ?>
        />
        <span>Enable SecurePress dashboard widgets</span>
    </label>

    <?php submit_button(__('Save Dashboard Widgets', 'securepress-companion')); ?>
</form>
            </div>

        </div>
    </div>
    <?php
}