<?php
/**
 * Activity logger module.
 */

if (!defined('ABSPATH')) {
    exit;
}

function securepress_companion_get_activity_logs() {
    $logs = get_option('securepress_companion_activity_logs', array());
    return is_array($logs) ? $logs : array();
}

function securepress_companion_add_activity_log($event_type, $message) {
    $logs = securepress_companion_get_activity_logs();

    $logs[] = array(
        'time'       => current_time('mysql'),
        'event_type' => sanitize_text_field($event_type),
        'message'    => sanitize_text_field($message),
        'user_id'    => get_current_user_id(),
        'ip'         => isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'])) : '',
    );

    $logs = array_slice(array_reverse($logs), 0, 50);
    $logs = array_reverse($logs);

    update_option('securepress_companion_activity_logs', $logs, false);
}

function securepress_companion_log_user_login($user_login, $user) {
    securepress_companion_add_activity_log(
        'user_login',
        'User ' . $user_login . ' logged in'
    );
}
add_action('wp_login', 'securepress_companion_log_user_login', 10, 2);

function securepress_companion_log_failed_login($username) {
    securepress_companion_add_activity_log(
        'failed_login',
        'Failed login attempt for username: ' . $username
    );
}
add_action('wp_login_failed', 'securepress_companion_log_failed_login');

function securepress_companion_log_plugin_activation($plugin) {
    securepress_companion_add_activity_log(
        'plugin_activation',
        'Plugin activated: ' . $plugin
    );
}
add_action('activated_plugin', 'securepress_companion_log_plugin_activation');

function securepress_companion_log_plugin_deactivation($plugin) {
    securepress_companion_add_activity_log(
        'plugin_deactivation',
        'Plugin deactivated: ' . $plugin
    );
}
add_action('deactivated_plugin', 'securepress_companion_log_plugin_deactivation');

function securepress_companion_log_theme_switch($new_name) {
    securepress_companion_add_activity_log(
        'theme_change',
        'Theme switched to: ' . $new_name
    );
}
add_action('switch_theme', 'securepress_companion_log_theme_switch');

function securepress_companion_log_user_creation($user_id) {
    $user = get_userdata($user_id);

    securepress_companion_add_activity_log(
        'user_creation',
        'New user created: ' . ($user ? $user->user_login : 'Unknown')
    );
}
add_action('user_register', 'securepress_companion_log_user_creation');

function securepress_companion_render_activity_logger_widget() {
    $logs = array_reverse(securepress_companion_get_activity_logs());
    ?>
    <div class="securepress-dashboard-widget">
        <p><strong>Recent Security Events</strong></p>

        <?php if (empty($logs)) : ?>
            <p>No security events logged yet.</p>
        <?php else : ?>
            <table class="widefat striped">
                <tbody>
                    <?php foreach (array_slice($logs, 0, 8) as $log) : ?>
                        <tr>
                            <td>
                                <strong><?php echo esc_html($log['message']); ?></strong><br>
                                <small>
                                    <?php echo esc_html($log['time']); ?>
                                    <?php if (!empty($log['ip'])) : ?>
                                        — IP: <?php echo esc_html($log['ip']); ?>
                                    <?php endif; ?>
                                </small>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <?php
}

function securepress_companion_register_activity_logger_widget() {
    wp_add_dashboard_widget(
        'securepress_companion_activity_logger',
        __('Recent Security Events', 'securepress-companion'),
        'securepress_companion_render_activity_logger_widget'
    );
}
add_action('wp_dashboard_setup', 'securepress_companion_register_activity_logger_widget');