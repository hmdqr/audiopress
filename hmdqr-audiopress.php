<?php
/**
 * Plugin Name: Hmdqr AudioPress
 * Plugin URI: https://github.com/hmdqr/hmdqr-audiopress-plugin
 * Description: A WordPress plugin for managing and displaying audio content.
 * Version: 1.0.0
 * Author: Hamad M H Al-Qassar
 * Author URI: https://hmdqr.me/
 * Text Domain: hmdqr-audiopress
 * Domain Path: /languages
 */

// Define constants
define( 'HMDQR_AUDIOPRESS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'HMDQR_AUDIOPRESS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include required files
require_once HMDQR_AUDIOPRESS_PLUGIN_DIR . 'includes/process.php';
require_once HMDQR_AUDIOPRESS_PLUGIN_DIR . 'includes/shortcode.php';

// Register custom post type
function hmdqr_audiopress_create_post_type() {
    $args = array(
        'labels' => array(
            'name' => __( 'Audio', 'hmdqr-audiopress' ),
            'singular_name' => __( 'Audio', 'hmdqr-audiopress' ),
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array( 'title', 'editor' ),
    );
    register_post_type( 'audio', $args );
}
add_action( 'init', 'hmdqr_audiopress_create_post_type' );

// Register custom user page template
function hmdqr_audiopress_user_template( $template ) {
    if ( is_page( 'my-audio' ) ) {
        $new_template = HMDQR_AUDIOPRESS_PLUGIN_DIR . 'templates/user-audio.php';
        if ( '' != $new_template ) {
            return $new_template ;
        }
    }
    return $template;
}
add_filter( 'page_template', 'hmdqr_audiopress_user_template' );

// Load plugin text domain
function hmdqr_audiopress_load_plugin_textdomain() {
    load_plugin_textdomain( 'hmdqr-audiopress', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'hmdqr_audiopress_load_plugin_textdomain' );

// Enqueue scripts and styles
function hmdqr_audiopress_enqueue_assets() {
    // Enqueue the plugin's styles
    wp_enqueue_style( 'hmdqr-audiopress-styles', HMDQR_AUDIOPRESS_PLUGIN_URL . 'assets/css/audiopress-styles.css', array(), '1.0.0' );

    // Enqueue the plugin's scripts
    wp_enqueue_script( 'hmdqr-audiopress-scripts', HMDQR_AUDIOPRESS_PLUGIN_URL . 'assets/js/audiopress-scripts.js', array( 'jquery' ), '1.0.0', true );

    // Localize the plugin's scripts to pass data from PHP to JavaScript
    wp_localize_script( 'hmdqr-audiopress-scripts', 'audiopress_ajax_data', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'hmdqr_audiopress_enqueue_assets' );

/**
 * Add settings page to Audiopress plugin.
 */
function hmdqr_audiopress_add_settings_page() {
  add_options_page(
    'Audiopress Settings',
    'Audiopress',
    'manage_options',
    'hmdqr-audiopress-settings',
    'hmdqr_audiopress_render_settings_page'
  );
}
add_action( 'admin_menu', 'hmdqr_audiopress_add_settings_page' );

/**
 * Render the settings page.
 */
function hmdqr_audiopress_render_settings_page() {
  ?>
  <div class="wrap">
    <h1><?php esc_html_e( 'Audiopress Settings', 'hmdqr-audiopress' ); ?></h1>
    <form method="post" action="options.php">
      <?php settings_fields( 'hmdqr-audiopress-settings-group' ); ?>
      <?php do_settings_sections( 'hmdqr-audiopress-settings-group' ); ?>
      <?php submit_button(); ?>
    </form>
  </div>
  <?php
}


/**
 * Register plugin settings.
 */
function hmdqr_audiopress_register_settings() {
  register_setting(
    'hmdqr-audiopress-settings-group',
    'hmdqr_audiopress_settings',
    'hmdqr_audiopress_sanitize_settings'
  );

  add_settings_section(
    'hmdqr-audiopress-settings-section',
    'Audiopress Settings',
    '',
    'hmdqr-audiopress-settings-group'
  );

  add_settings_field(
    'hmdqr_audiopress_upload_directory',
    'Upload Directory',
    'hmdqr_audiopress_upload_directory_callback',
    'hmdqr-audiopress-settings-group',
    'hmdqr-audiopress-settings-section'
  );
}
add_action( 'admin_init', 'hmdqr_audiopress_register_settings' );

/**
 * Sanitize the settings.
 */
function hmdqr_audiopress_sanitize_settings( $settings ) {
  // Sanitize the upload directory setting.
  $settings['upload_directory'] = sanitize_text_field( $settings['upload_directory'] );

  return $settings;
}

/**
 * Render the upload directory setting.
 */
function hmdqr_audiopress_upload_directory_callback() {
  $settings = get_option( 'hmdqr_audiopress_settings' );
  ?>
  <input type="text" name="hmdqr_audiopress_settings[upload_directory]" value="<?php echo esc_attr( $settings['upload_directory'] ); ?>" />
  <?php
}
