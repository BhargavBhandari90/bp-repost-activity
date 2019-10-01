var currentRequest = null;

( function ( $ ) {

	"use strict";

	window.BP_Repost = {

		init: function() {

			this.bprpa_repost();
			this.bprpa_set_param();

		},

		bprpa_set_param: function() {

			$.ajaxPrefilter( function ( options, originalOptions, jqXHR ) {

				// Check if form is available or not.
				if ( $( '.bp-repost-activity' ).length == 0 ) {
					return true;
				}


				// Modify options, control originalOptions, store jqXHR, etc
				try {
					if ( originalOptions.data == null || typeof (originalOptions.data) === 'undefined' || typeof (originalOptions.data.action) === 'undefined' ) {
						return true;
					}
				} catch ( e ) {
					return true;
				}

				var original_activity_id = $( '.bp-repost-activity.repost-selected' ).data( 'activity_id' );

				// Set form data into activity ajax.
				if ( typeof( original_activity_id ) !== 'undefined' && originalOptions.data.action === 'post_update' ) {
					options.data += '&original_item_id=' + original_activity_id + '&content=repost';
				}

			} );

		},

		bprpa_repost: function() {

			$( document ).on( 'click', '.bp-repost-activity', function( e ){

				e.preventDefault();

				if ( $( '.bp-repost-activity' ).hasClass( 'repost-selected' ) ) {
					$( '.bp-repost-activity' ).removeClass( 'repost-selected' );
				}

				$( this ).addClass( 'repost-selected' );

				$('#aw-whats-new-submit').trigger( 'click' );

			});

		},

	};

	$( document ).on( 'ready', function () {

		BP_Repost.init();

	});


} )( jQuery );