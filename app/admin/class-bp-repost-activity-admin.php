<?php
/**
 * Class for repost methods.
 *
 * @package Bp_Repost_Activity
 */

/**
 * Exit if accessed directly
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// If class is exist, then don't execute this.
if ( ! class_exists( 'BP_Repost_Activity_Admin' ) ) {

	class BP_Repost_Activity_Admin {

		/**
		 * Constructor for class.
		 */
		public function __construct() {

			// Add settings.
			add_action( 'bp_register_admin_settings', array( $this, 'bprpa_register_admin_settings' ) );

		}

		/**
		 * Add setting for emable/disable re-post functionality.
		 */
		public function bprpa_register_admin_settings() {

			if ( bp_is_active( 'activity' ) ) {

				// Activity Heartbeat refresh.
				add_settings_field(
					'_bprpa_enable_setting',
					__( 'Re-Post Activity', 'bp-repost-activity' ),
					array( $this, 'bprpa_enable_disable_setting' ),
					'buddypress',
					'bp_activity'
				);

				register_setting( 'buddypress', '_bprpa_enable_setting', 1 );

			}

		}

		/**
		 * Setting markup in BuddyPress Settings page.
		 */
		public function bprpa_enable_disable_setting() {

			?>

				<input id="_bprpa_enable_setting" name="_bprpa_enable_setting" type="checkbox" value="1" <?php checked( $this->bprpa_enable_disable_option_value( 1 ) ); ?> />
				<label for="_bprpa_enable_setting">
					<?php esc_html_e( 'Allow users to re-post activity from activity stream', 'bp-repost-activity' ); ?>
				</label>

			<?php
		}

		/**
		 * Are re-post activity disabled?
		 *
		 * @param bool $default Optional. Fallback value if not found in the database.
		 *                      Default: false.
		 * @return bool True if activity comments are disabled for blog and forum
		 *              items, otherwise false.
		 */
		function bprpa_enable_disable_option_value( $default = 0 ) {

			/**
			 * Filters whether or not re-post activity is disabled.
			 *
			 * @param bool $value Whether or not re-post activity is disabled.
			 */
			return apply_filters( 'bprpa_enable_disable_option_value', bp_get_option( '_bprpa_enable_setting', $default ) );
		}

	}

}

new BP_Repost_Activity_Admin();
