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

	}

	public function bprpa_repost_button() {
		printf(
			'<a class="button bp-repost-activity" title="%s" href="#" data-activity_id="%d">%s&nbsp;<i class="fa fa-retweet" aria-hidden="true"></i></a>',
			esc_html__( 'Re-post Activity', 'bp-repost-activity' ),
			intval( bp_get_activity_id() ),
			esc_html__( 'Re-post', 'bp-repost-activity' )
		);
	}

	public function bprpa_enqueue_styles_scripts() {
		// Core plugin custom style
		wp_enqueue_style(
			'font-awesome',
			'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css'
		);

		// wp_enqueue_script( 'jquery-ui-tooltip' );

		wp_enqueue_script(
			'repost-script',
			BPRPA_URL . 'assets/js/custom.min.js',
			'',
			'',
			true
		);
	}

}

new BP_Repost_Activity();