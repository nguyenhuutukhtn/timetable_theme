<?php
/**
 * Plugin Name: Lucky Wheel with Registration Form
 * Description: Implements a lucky wheel feature with a registration form
 * Version: 2.1
 * Author: Your Name
 */

function lucky_wheel_enqueue_scripts()
{
    wp_enqueue_style('lucky-wheel-style', plugin_dir_url(__FILE__) . 'css/lucky-wheel.css');
    wp_enqueue_script('lucky-wheel-script', plugin_dir_url(__FILE__) . 'js/lucky-wheel.js', array('jquery'), '2.1', true);
    
    $wheel_config = apply_filters('lucky_wheel_config', array());
    wp_localize_script('lucky-wheel-script', 'lucky_wheel_config', $wheel_config);

    wp_localize_script('lucky-wheel-script', 'lucky_wheel_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'lucky_wheel_enqueue_scripts');

function lucky_wheel_shortcode() {
    ob_start();
    
    // Get form fields from settings
    $form_fields = get_option('lucky_wheel_form_fields', [
        ['type' => 'text', 'label' => 'Name', 'required' => true],
        ['type' => 'email', 'label' => 'Email', 'required' => true],
        ['type' => 'tel', 'label' => 'Phone', 'required' => true],
        ['type' => 'text', 'label' => 'Website', 'required' => false]
    ]);

    // Get Facebook page URL from settings
    $facebook_page = get_option('lucky_wheel_facebook_page', 'https://www.facebook.com/your-fb-page');

    ?>
    <div class="lucky-wheel-wrapper">
        <div class="lucky-wheel-container">
            <div id="registration-form" class="registration-form">
                <h2>Vui lòng nhập thông tin để bắt đầu trò chơi</h2>
                <form id="lucky-wheel-form">
                    <?php foreach ($form_fields as $field): ?>
                        <input 
                            type="<?php echo esc_attr($field['type']); ?>" 
                            name="<?php echo esc_attr(strtolower($field['label'])); ?>" 
                            placeholder="<?php echo esc_attr($field['label']); ?>"
                            <?php echo isset($field['required']) && $field['required'] ? 'required' : ''; ?>
                        >
                    <?php endforeach; ?>
                    <button type="submit">Bắt đầu</button>
                </form>
            </div>
            <div id="lucky-wheel-game" class="lucky-wheel-game">
                <div class="wheel-overlay"></div>
                <canvas id="lucky-wheel" width="300" height="300"></canvas>
                <div id="wheel-center" class="wheel-center">
                    <span id="spin-text">SPIN</span>
                </div>
                <div class="wheel-pointer">
                    <div class="pointer-triangle"></div>
                </div>
                <div class="pointer-base">
                    <div class="pointer-base-inner"></div>
                </div>
            </div>
        </div>
        <div id="result" class="result-display"></div>
        <!-- facebook link to contact receive prize -->
        <div id="fb-link" class="fb-link hidden">
            <a href="<?php echo esc_url($facebook_page); ?>" target="_blank">Liên hệ nhận quà</a>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('lucky_wheel', 'lucky_wheel_shortcode');

function handle_registration_form()
{
    // Process form data here
    wp_send_json_success('Registration successful!');
}
add_action('wp_ajax_lucky_wheel_register', 'handle_registration_form');
add_action('wp_ajax_nopriv_lucky_wheel_register', 'handle_registration_form');

// Add admin menu
function lucky_wheel_admin_menu() {
    add_options_page('Lucky Wheel Settings', 'Lucky Wheel', 'manage_options', 'lucky-wheel', 'lucky_wheel_settings_page');
}
add_action('admin_menu', 'lucky_wheel_admin_menu');

// Include admin settings file
$admin_settings_file = plugin_dir_path(__FILE__) . 'admin/settings.php';
if (file_exists($admin_settings_file)) {
    require_once $admin_settings_file;
} else {
    add_action('admin_notices', function() {
        echo '<div class="error"><p>Lucky Wheel: Admin settings file is missing. Please check the plugin installation.</p></div>';
    });
}

// Enqueue admin scripts and styles
function lucky_wheel_admin_enqueue_scripts($hook) {
    if ('settings_page_lucky-wheel' !== $hook) {
        return;
    }
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');
    wp_enqueue_script('lucky-wheel-admin', plugin_dir_url(__FILE__) . 'js/lucky-wheel-admin.js', array('jquery', 'wp-color-picker'), '1.0', true);
}
add_action('admin_enqueue_scripts', 'lucky_wheel_admin_enqueue_scripts');

// Add settings to lucky_wheel_config
function lucky_wheel_add_config($config) {
    $segments = get_option('lucky_wheel_segments', [
        ['color' => "#FF6B6B", 'label' => "Prize 1"],
        ['color' => "#4ECDC4", 'label' => "Prize 2"],
        ['color' => "#45B7D1", 'label' => "Prize 3"],
        ['color' => "#F7DC6F", 'label' => "Prize 4"],
        ['color' => "#B8E994", 'label' => "Prize 5"],
        ['color' => "#FF9FF3", 'label' => "Prize 6"]
    ]);

    $config['segments'] = $segments;
    
    // Add this line for debugging
    error_log('PHP Wheel config: ' . print_r($config, true));
    
    return $config;
}
add_filter('lucky_wheel_config', 'lucky_wheel_add_config');