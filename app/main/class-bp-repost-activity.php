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
if ( ! class_exists( 'BP_Repost_Activity' ) ) {

	class BP_Repost_Activity {

		/**
		 * Constructor for class.
		 */
		public function __construct() {

			// Add repost button.
			add_action( 'bp_activity_entry_meta', array( $this, 'bprpa_repost_button' ) );

			// Add custom script.
			add_action( 'wp_enqueue_scripts', array( $this, 'bprpa_enqueue_styles_scripts' ) );

			// Add content for public activity.
			add_action( 'bp_activity_new_update_content', array( $this, 'bprpa_repost_activity_content' ), 10 );

			// Add content for group activity.
			add_action( 'groups_activity_new_update_content', array( $this, 'bprpa_repost_activity_content' ), 10 );

			// Add popup mokup in footer.
			add_action( 'wp_footer', array( $this, 'bprpa_popup_markup' ) );

		}

		/**
		 * Markup for popup.
		 */
		public function bprpa_popup_markup() {

			// Bail, if anything goes wrong.
			if ( ! $this->bprpa_is_activity_strem() || ! function_exists( 'buddypress' ) ) {
				return;
			}

			?>
			<div id="repost-box" class="modal fade" role="dialog">
			  <div class="modal-dialog">
				<form id="repost-activity-form">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>

							<?php esc_html_e( 'Post in', 'bp-repost-activity' ) ?>:
							<select name="posting_at" id="posting_at">
								<option value="">
									<?php esc_html_e( 'Public', 'bp-repost-activity' ) ?>
								</option>
								<option value="groups">
									<?php esc_html_e( 'Group', 'bp-repost-activity' ) ?>
								</option>
							</select>

							<select name="rpa_group_id" id="rpa_group_id" style="display: none;">
								<?php if ( bp_has_groups( 'user_id=' . bp_loggedin_user_id() . '&type=alphabetical&max=100&per_page=100&populate_extras=0&update_meta_cache=0' ) ) :
									while ( bp_groups() ) : bp_the_group(); ?>

										<option value="<?php bp_group_id(); ?>"><?php bp_group_name(); ?></option>

									<?php endwhile;
								endif; ?>
							</select>
						</div>
						<div class="modal-body">
							<input type="hidden" name="original_item_id" id="original_item_id" value="" />
							<div class="content"></div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal"><?php esc_html_e( 'Close', 'bp-repost-activity' ) ?></button>
							<button type="submit" id="repost-activity" name="repost-activity"><?php esc_html_e( 'Post', 'bp-repost-activity' ) ?></button>
						</div>
					</div>
				</form>
			  </div> <!-- End .modal-dialog -->
			</div> <!-- End #repost-box -->
			<?php
		}

		/**
		 * Button for re-post activity.
		 */
		public function bprpa_repost_button() {

			// Bail, if anything goes wrong.
			if ( ! $this->bprpa_is_activity_strem() ) {
				return;
			}

			// Markup for button.
			printf(
				'<a class="button bp-repost-activity" href="#" data-toggle="modal" data-target="#repost-box" data-activity_id="%d">%s&nbsp;<span class="dashicons dashicons-controls-repeat"></span></a>',
				intval( bp_get_activity_id() ),
				esc_html__( 'Re-Post', 'bp-repost-activity' )
			);
		}

		/**
		 * Add scripts & css related to re-post button.
		 */
		public function bprpa_enqueue_styles_scripts() {

			// Bail, if anything goes wrong.
			if ( ! $this->bprpa_is_activity_strem() ) {
				return;
			}

			// Custom plugin script.
			wp_enqueue_script(
				'repost-script',
				BPRPA_URL . 'assets/js/custom.min.js',
				'',
				'',
				true
			);

			// Bootstrap js.
			wp_enqueue_script(
				'bootstrap-script',
				BPRPA_URL . 'assets/js/bootstrap.min.js',
				array( 'jquery' ),
				'',
				true
			);

			// Custom style.
			wp_enqueue_style(
				'repost-style',
				BPRPA_URL . 'assets/css/style.min.css'
			);

			// Bootstrap css.
			wp_enqueue_style(
				'bootstrap-style',
				BPRPA_URL . 'assets/css/bootstrap.min.css'
			);

		}

		/**
		 * Set content from original activity.
		 *
		 * @param  string $content
		 * @return string
		 */
		public function bprpa_repost_activity_content( $content ) {

			// Bail, if anything goes wrong.
			if ( ! $this->bprpa_is_activity_strem() ) {
				return $content;
			}

			// Get activity id which we are going to re-post.
			$original_item_id = filter_input( INPUT_POST , 'original_item_id', FILTER_SANITIZE_NUMBER_INT );

			// Return if it's blank.
			if ( empty( $original_item_id ) ) {
				return $content;
			}

			// Get activity by activity ID.
			$activity = $this->bprpa_get_activity( $original_item_id );

			if ( empty( $activity ) ) {
				return $content;
			}

			// Get content.
			$content = ! empty( $activity->content ) ? $activity->content : '&nbsp;';

			/**
			 * Filters the new activity content for reposted activity item.
			 *
			 * @param string $content Activity content from original activity.
			 */
			$content = apply_filters( 'bprpa_activity_content', $content, $original_item_id );

			return $content;

		}

		/**
		 * Get activity by activity id.
		 *
		 * @param  int $activty_id
		 * @return obj
		 */
		public function bprpa_get_activity( $activty_id = '' ) {

			// Bail, if anything goes wrong.
			if ( ! $this->bprpa_is_activity_strem() || empty( $activty_id ) ) {
				return;
			}

			global $wpdb;

			// Activity table
			$activty_table = $wpdb->prefix . 'bp_activity';

			// Sql query for getting activity record by activity id.
			$activity_sql = $wpdb->prepare(
				"SELECT * FROM {$activty_table} WHERE id = %d",
				intval( $activty_id )
			);

			// Get result.
			$activity = $wpdb->get_row( $activity_sql );

			return $activity;

		}

		/**
		 * Check if the page is activity stream of user activity, group activity or main activity.
		 *
		 * @return bool
		 */
		public function bprpa_is_activity_strem() {

			// Bail, if anything goes wrong.
			if ( ! function_exists( 'bp_is_current_component' ) ||
				 ! function_exists( 'bp_is_single_activity' ) ||
				 ! function_exists( 'bp_is_group_activity' ) ||
				 ! function_exists( 'bp_get_option' ) ) {

				return false;

			}

			// Check if it's enabled from BuddyPress Settings.
			if ( '1' != bp_get_option( '_bprpa_enable_setting', 1 ) ) {
				return false;
			}

			// If it's activity stram of user activity, group activity or main activity
			if ( is_user_logged_in() &&
				 bp_is_current_component('activity') &&
				 ! bp_is_single_activity() ||
				 bp_is_group_activity() ) {

				return true;

			}

			return false;

		}

	}

}

new BP_Repost_Activity();
