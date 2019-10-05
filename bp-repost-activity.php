<?php
/**
 * Plugin Name:     BuddyPress Re-post Activity
 * Plugin URI:      https://bhargavb.wordpress.com/
 * Description:     Re-post activity.
 * Author:          Bunty
 * Author URI:      https://bhargavb.wordpress.com/
 * Text Domain:     bp-repost-activity
 * Domain Path:     /languages
 * Version:         1.0.0
 *
 * @package         Bp_Repost_Activity
 */

/**
 * Main file, contains the plugin metadata and activation processes
 *
 * @package    Bp_Repost_Activity
 */
if ( ! defined( 'BPRPA_VERSION' ) ) {
	/**
	 * The version of the plugin.
	 */
	define( 'BPRPA_VERSION', '1.0.0' );
}

if ( ! defined( 'BPRPA_PATH' ) ) {
	/**
	 *  The server file system path to the plugin directory.
	 */
	define( 'BPRPA_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'BPRPA_URL' ) ) {
	/**
	 * The url to the plugin directory.
	 */
	define( 'BPRPA_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'BPRPA_BASE_NAME' ) ) {
	/**
	 * The url to the plugin directory.
	 */
	define( 'BPRPA_BASE_NAME', plugin_basename( __FILE__ ) );
}

/**
 * Apply transaltion file as per WP language.
 */
function bprpa_text_domain_loader() {

	// Get mo file as per current locale.
    $mofile = BPRPA_PATH . 'languages/' . get_locale() .'.mo';

    // If file does not exists, then applu default mo.
    if ( ! file_exists( $mofile ) ) {
    	$mofile = BPRPA_PATH . 'languages/default.mo';
    }

    load_textdomain( 'bp-repost-activity', $mofile );
}

add_action('plugins_loaded', 'bprpa_text_domain_loader');

// Include functions file.
require BPRPA_PATH . 'app/main/class-bp-repost-activity.php';
require BPRPA_PATH . 'app/admin/class-bp-repost-activity-admin.php';
