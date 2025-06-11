<?php
/**
 * Plugin Name:     Re-post Activity for BuddyPress
 * Description:     Twitter like Re-post activity for BuddyPress. Compatible with BuddyBoss as well.
 * Author:          Bunty
 * Author URI:      https://biliplugins.com/
 * Text Domain:     bp-repost-activity
 * Domain Path:     /languages
 * Version:         1.4.0
 *
 * @package         Bp_Repost_Activity
 */

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main file, contains the plugin metadata and activation processes
 *
 * @package    Bp_Repost_Activity
 */
if ( ! defined( 'BPRPA_VERSION' ) ) {
	/**
	 * The version of the plugin.
	 */
	define( 'BPRPA_VERSION', '1.4.0' );
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
	$mofile = BPRPA_PATH . 'languages/' . get_locale() . '.mo';

	// If file does not exists, then applu default mo.
	if ( ! file_exists( $mofile ) ) {
		$mofile = BPRPA_PATH . 'languages/default.mo';
	}

	load_textdomain( 'bp-repost-activity', $mofile );
}

add_action( 'plugins_loaded', 'bprpa_text_domain_loader' );

/**
 * Display admin notice if BuddyPress is not activated.
 */
function bprpa_admin_notice_error() {

	if ( function_exists( 'bp_is_active' ) ) {
		return;
	}

	// Notice class.
	$class = 'notice notice-error';

	// Get plugin name.
	$plugin_data = get_plugin_data( __FILE__ );
	$plugin_name = $plugin_data['Name'];

	$message = sprintf(
		/* translators: %1$s: Plugin's name, %2$s: Plugin's name */
		__( '%1$s works with BuddyPress only. Please activate BuddyPress or de-activate %2$s.', 'bp-repost-activity' ),
		esc_html( $plugin_name ),
		esc_html( $plugin_name )
	);

	printf(
		'<div class="%1$s"><p>%2$s</p></div>',
		esc_attr( $class ),
		esc_html( $message )
	);
}

add_action( 'admin_notices', 'bprpa_admin_notice_error' );

// Include functions file.
require BPRPA_PATH . 'app/includes/common-functions.php';
require BPRPA_PATH . 'app/main/class-bp-repost-activity.php';
require BPRPA_PATH . 'app/admin/class-bp-repost-activity-admin.php';
