<?php
/**
 * Class for repost methods.
 *
 * @package Bp_Repost_Activity
 */
class BP_Repost_Activity {

	/**
	 * Constructor for class.
	 */
	public function __construct() {

		add_action( 'bp_activity_entry_meta', array( $this, 'bprpa_repost_button' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'bprpa_enqueue_styles_scripts' ) );

		add_action( 'bp_activity_new_update_content', array( $this, 'bprpa_repost_activity' ), 10 );

		add_action( 'wp_footer', array( $this, 'bprpa_popup_markup' ) );

	}

	public function bprpa_popup_markup() {
		add_thickbox(); ?>
		<div id="repost-box" class="modal fade" role="dialog">
		  <div class="modal-dialog">
		  	<form id="repost-activity-form">
		    <!-- Modal content-->
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal">&times;</button>
		        <select name="posting_at" id="posting_at">
	     			<option value="public">Public</option>
	     			<option value="group">Group</option>
	     		</select>
		      </div>
		      <div class="modal-body">
		     		<input type="hidden" name="original_item_id" id="original_item_id" value="" />
		     		<input type="hidden" name="content" value="repost" />
		     		<div class="content">This is test text...</div>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		        <button type="submit" id="repost-activity" name="repost-activity">Post</button>
		      </div>
		    </div>
		    </form>
		  </div>
		</div>
		<?php
	}

	public function bprpa_repost_button() {
		printf(
			'<a class="button bp-repost-activity" href="#" data-toggle="modal" data-target="#repost-box" data-activity_id="%d">%s&nbsp;<span class="dashicons dashicons-controls-repeat"></span></a>',
			intval( bp_get_activity_id() ),
			esc_html__( 'Re-Post', 'bp-repost-activity' )
		);
	}

	public function bprpa_enqueue_styles_scripts() {
		// Core plugin custom style
		// wp_enqueue_style(
		// 	'font-awesome',
		// 	'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css'
		// );

		// wp_enqueue_script( 'jquery-ui-tooltip' );

		wp_enqueue_script(
			'repost-script',
			BPRPA_URL . 'assets/js/custom.min.js',
			'',
			'',
			true
		);

		wp_enqueue_script(
			'bootstrap-script',
			BPRPA_URL . 'assets/js/bootstrap.min.js',
			array( 'jquery' ),
			'',
			true
		);

		wp_enqueue_style(
			'repost-style',
			BPRPA_URL . 'assets/css/style.min.css',
		);

		wp_enqueue_style(
			'bootstrap-style',
			BPRPA_URL . 'assets/css/bootstrap.min.css',
		);
	}

	public function bprpa_repost_activity( $content ) {

		$original_item_id = filter_input( INPUT_POST , 'original_item_id', FILTER_SANITIZE_NUMBER_INT );

		if ( empty( $original_item_id ) ) {
			return $content;
		}

		$activity = $this->bprpa_get_activity( $original_item_id );

		if ( empty( $activity ) ) {
			return $content;
		}

		$content = ! empty( $activity->content ) ? $activity->content : '&nbsp;';

		return $content;

	}

	public function bprpa_get_activity( $activty_id = '' ) {

		if ( empty( $activty_id ) ) {
			return;
		}

		global $wpdb;

		$activty_table = $wpdb->prefix . 'bp_activity';

		$activity_sql = $wpdb->prepare(
			"SELECT * FROM {$activty_table} WHERE id = %d",
			intval( $activty_id )
		);

		$activity = $wpdb->get_row( $activity_sql );

		return $activity;

	}

}

new BP_Repost_Activity();