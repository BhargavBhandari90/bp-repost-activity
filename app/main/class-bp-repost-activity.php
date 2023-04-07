<?php
/**
 * Class for repost methods.
 *
 * @package Bp_Repost_Activity
 */

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// If class is exist, then don't execute this.
if ( ! class_exists( 'BP_Repost_Activity' ) ) {

	/**
	 * Class for Activity Re-post.
	 */
	class BP_Repost_Activity {

		/**
		 * Constructor for class.
		 */
		public function __construct() {

			// Add repost button.
			add_action( 'bp_nouveau_get_activity_entry_buttons', array( $this, 'bprpa_repost_button' ), 10, 2 );

			// Add custom script.
			add_action( 'wp_enqueue_scripts', array( $this, 'bprpa_enqueue_styles_scripts' ), 99 );

			// Add content for public activity.
			add_action( 'bp_activity_new_update_content', array( $this, 'bprpa_repost_activity_content' ), 10 );

			// Add content for group activity.
			add_action( 'groups_activity_new_update_content', array( $this, 'bprpa_repost_activity_content' ), 10 );

			// Add popup mokup in footer.
			add_action( 'wp_footer', array( $this, 'bprpa_popup_markup' ) );

			add_action( 'bp_activity_posted_update', array( $this, 'bprpa_save_media' ), 10, 3 );

			// Save repost activity status in meta.
			add_action( 'bp_activity_posted_update', array( $this, 'bprpa_save_repost_status' ), 10, 3 );
			add_action( 'bp_groups_posted_update', array( $this, 'bprpa_save_group_repost_status' ), 10, 4 );

			// Add repost status on Activity Header.
			add_filter( 'bp_get_activity_action', array( $this, 'bprpa_show_repost_status' ), 10 );

		}

		/**
		 * Markup for popup.
		 */
		public function bprpa_popup_markup() {

			// Bail, if anything goes wrong.
			if ( ! $this->bprpa_is_activity_strem() || ! function_exists( 'buddypress' ) ) {
				return;
			}
			$if_bp_has_group = bp_is_active( 'groups' ) && bp_has_groups( 'user_id=' . bp_loggedin_user_id() . '&type=alphabetical&max=100&per_page=100&populate_extras=0&update_meta_cache=0' );
			?>
			<div id="repost-box" class="modal" role="dialog">
				<div class='modal-dialog'>
					<form id="repost-activity-form">
						<!-- Modal content-->
						<div class="modal-content">
							<div class="modal-header">
								<span type="button" class="close" data-dismiss="modal">&times;</span>
								<?php esc_html_e( 'Post in', 'bp-repost-activity' ); ?>:
								<select class="form-control" name="posting_at" id="posting_at">
									<option value="">
										<?php esc_html_e( 'Public', 'bp-repost-activity' ); ?>
									</option>
									<?php if ( $if_bp_has_group ) : ?>
									<option value="groups">
										<?php esc_html_e( 'Group', 'bp-repost-activity' ); ?>
									</option>
									<?php endif; ?>
								</select>
								<?php if ( $if_bp_has_group ) : ?>
								<select name="rpa_group_id" id="rpa_group_id" style="display: none;">
									<?php while ( bp_groups() ) : ?>
										<?php bp_the_group(); ?>
										<option value="<?php bp_group_id(); ?>"><?php bp_group_name(); ?></option>
									<?php endwhile; ?>
								</select>
								<?php endif; ?>
							</div>
							<div class="modal-body">
								<input type="hidden" name="original_item_id" id="original_item_id" value="" />
								<div class="content"></div>
							</div>
							<div class="modal-footer">
								<button type="button" id="bprpa-close-modal" class="btn btn-default" data-dismiss="modal"><?php esc_html_e( 'Close', 'bp-repost-activity' ); ?></button>
								<button type="submit" id="repost-activity" name="repost-activity"><?php esc_html_e( 'Post', 'bp-repost-activity' ); ?></button>
							</div>
						</div>
					</form>
				</div><!-- End .modal-dialog -->
			</div> <!-- End #repost-box -->
			<?php
		}

		/**
		 * Button for re-post activity.
		 *
		 * @param array $buttons     The list of buttons.
		 * @param int   $activity_id The current activity ID.
		 */
		public function bprpa_repost_button( $buttons, $activity_id ) {

			// Bail, if anything goes wrong.
			if ( ! $this->bprpa_is_activity_strem() || function_exists( 'bp_get_activity_type' ) && 'activity_update' !== bp_get_activity_type() ) {
				return $buttons;
			}

			$buttons['bp_activity_report'] = array(
				'id'                => 'bp_activity_report',
				'position'          => 99,
				'component'         => 'activity',
				'parent_element'    => 'div',
				'parent_attr'       => array(),
				'must_be_logged_in' => true,
				'button_element'    => 'a',
				'button_attr'       => array(
					'class'            => 'button item-button bp-secondary-action bp-tooltip bp-repost-activity',
					'id'               => esc_attr( 'bp_activity_repost_' . $activity_id ),
					'data-bp-tooltip'  => esc_html__( 'Re-post', 'bp-repost-activity' ),
					'data-activity_id' => esc_attr( $activity_id ),
					'aria-pressed'     => 'false',

				),
				'link_text'         => sprintf(
					'<span class="bp-screen-reader-text">%s</span>',
					esc_html__( 'Re-Post', 'bp-repost-activity' )
				),
			);

			return $buttons;
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
				BPRPA_URL . 'assets/js/custom.js',
				'',
				BPRPA_VERSION,
				true
			);

			// Bootstrap js.
			wp_enqueue_script(
				'bootstrap-script',
				BPRPA_URL . 'assets/js/modal.js',
				array( 'jquery' ),
				BPRPA_VERSION,
				true
			);

			// Custom style.
			wp_enqueue_style(
				'repost-style',
				BPRPA_URL . 'assets/css/style.css',
				'',
				BPRPA_VERSION,
				''
			);

			// Set params to be used in custom script.
			$params = array(
				'theme_package_id' => function_exists( 'bp_get_option' )
					? bp_get_option( '_bp_theme_package_id', 'legacy' )
					: 'legacy',
			);

			wp_localize_script( 'repost-script', 'RE_Post_Activity', $params );

		}

		/**
		 * Set content from original activity.
		 *
		 * @param  string $content Activity content.
		 * @return string
		 */
		public function bprpa_repost_activity_content( $content ) {

			// Bail, if anything goes wrong.
			if ( ! $this->bprpa_is_activity_strem() ) {
				return $content;
			}

			// Get activity id which we are going to re-post.
			$original_item_id = filter_input( INPUT_POST, 'original_item_id', FILTER_SANITIZE_NUMBER_INT );

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

			/**
			 * To allow media to be saved while re-posting.
			 * Removed this action, because while reposting medias,
			 * we will have links in our copied content.
			 * So we don't want to moderate those media links while re-posting.
			 */
			remove_action( 'bp_activity_before_save', 'bp_activity_check_moderation_keys', 2, 1 );

			return $content;

		}

		/**
		 * Get activity by activity id.
		 *
		 * @param  int $activty_id Activity ID.
		 * @return obj
		 */
		public function bprpa_get_activity( $activty_id = '' ) {

			// Bail, if anything goes wrong.
			if ( ! $this->bprpa_is_activity_strem() || empty( $activty_id ) ) {
				return;
			}

			// Get result from transient.
			$activity = get_transient( 'bprpa_activity_' . $activty_id );

			if ( false !== $activity ) {
				return $activity;
			}

			global $wpdb;

			// Activity table.
			$activty_table = $wpdb->prefix . 'bp_activity';

			// Sql query for getting activity record by activity id.
			$activity = $wpdb->get_row(
				$wpdb->prepare(
					"SELECT * FROM {$activty_table} WHERE id = %d", // @codingStandardsIgnoreLine
					intval( $activty_id )
				)
			);

			// Set transient.
			if ( ! empty( $activity ) ) {
				set_transient( 'bprpa_activity_' . $activty_id, $activity, 24 * HOUR_IN_SECONDS );
			}

			return $activity;

		}

		/**
		 * Get activity by activity id.
		 *
		 * @param  int $activity_id Activity ID.
		 * @return obj
		 */
		public function bprpa_get_media( $activity_id = '' ) {

			// Bail, if anything goes wrong.
			if ( ! $this->bprpa_is_activity_strem() || empty( $activity_id ) ) {
				return;
			}

			$media = get_transient( 'bprpa_media_activity_' . $activity_id );

			if ( false !== $media ) {
				return $media;
			}

			global $wpdb;

			// Activity table.
			$media_table = $wpdb->prefix . 'rt_rtm_media';

			// Sql query for getting activity record by activity id.
			$media = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT * FROM {$media_table} WHERE activity_id = %d", // @codingStandardsIgnoreLine
					intval( $activity_id )
				),
				ARRAY_A
			);

			if ( ! empty( $media ) ) {
				set_transient( 'bprpa_media_activity_' . $activity_id, $media, 24 * HOUR_IN_SECONDS );
			}

			return $media;

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
			if ( '1' !== bp_get_option( '_bprpa_enable_setting', 1 ) ) {
				return false;
			}

			// If it's activity stram of user activity, group activity or main activity.
			if ( is_user_logged_in() &&
				bp_is_current_component( 'activity' ) &&
				! bp_is_single_activity() ||
				bp_is_group_activity() ) {

				return true;

			}

			return false;

		}

		/**
		 * Clone rtmedia data.
		 *
		 * @param  string $updated_content Activity content.
		 * @param  int    $user_id         User ID.
		 * @param  int    $activity_id     Activity ID.
		 * @return void
		 */
		public function bprpa_save_media( $updated_content, $user_id, $activity_id ) {

			// Bail, if anything goes wrong.
			if ( ! class_exists( 'RTMediaBuddyPressActivity' ) ||
				empty( $user_id ) ||
				empty( $activity_id ) ) {
				return;
			}

			// Get activity id which we are going to re-post.
			$original_item_id = filter_input( INPUT_POST, 'original_item_id', FILTER_SANITIZE_NUMBER_INT );

			if ( empty( $original_item_id ) ) {
				return;
			}

			/* Save media */
			$media = $this->bprpa_get_media( $original_item_id );

			if ( ! empty( $media ) ) {

				global $wpdb;

				// Media table.
				$media_table = $wpdb->prefix . 'rt_rtm_media';

				foreach ( $media as $copied_media ) {

					if ( isset( $copied_media['id'] ) ) {
						unset( $copied_media['id'] );
					}

					// Set new activity id.
					if ( isset( $copied_media['activity_id'] ) ) {
						unset( $copied_media['activity_id'] );
						$copied_media['activity_id'] = $activity_id;
					}

					// Set new activity author id.
					if ( isset( $copied_media['media_author'] ) ) {
						unset( $copied_media['media_author'] );
						$copied_media['media_author'] = $user_id;
					}

					// Insert data.
					$wpdb->insert(
						$media_table,
						$copied_media
					);

				}

				$media_activity_text = bp_activity_get_meta( $original_item_id, 'bp_activity_text' );

				// Update activity text.
				if ( ! empty( $media_activity_text ) ) {
					bp_activity_update_meta( $activity_id, 'bp_activity_text', bp_activity_filter_kses( $media_activity_text ) );
				}
			}
			/* End Save media */

		}

		/**
		 * Save Repost activity status in meta.
		 *
		 * @param  string $updated_content Activity content.
		 * @param  int    $user_id         User ID.
		 * @param  int    $activity_id     Activity ID.
		 * @return void
		 */
		public function bprpa_save_repost_status( $updated_content, $user_id, $activity_id ) {
			if ( 'repost' === $updated_content ) {
				bp_activity_update_meta( $activity_id, 'bp_activity_reposted', true );
			}
		}

		/**
		 * Save Group repost activity status in meta.
		 *
		 * @param  string $updated_content Activity content.
		 * @param  int    $user_id         User ID.
		 * @param  int    $group_id        Group ID.
		 * @param  int    $activity_id     Activity ID.
		 * @return void
		 */
		public function bprpa_save_group_repost_status( $updated_content, $user_id, $group_id, $activity_id ) {
			if ( 'repost' === $updated_content ) {
				bp_activity_update_meta( $activity_id, 'bp_activity_reposted', true );
			}
		}

		/**
		 * Show Reposted Text on Activity header.
		 *
		 * @param string $text Previous bp_get_activity_action Text.
		 */
		public function bprpa_show_repost_status( $text ) {
			$activity_id   = bp_get_activity_id();
			$repost_status = bp_activity_get_meta( $activity_id, 'bp_activity_reposted', true );

			if ( ! $repost_status ) {
				return $text;
			}

			return $text . ' <span class="dashicons dashicons-controls-repeat bprpa-share-icon bp-tooltip" data-bp-tooltip="Reposted"></span>';
		}
	}

}

new BP_Repost_Activity();
