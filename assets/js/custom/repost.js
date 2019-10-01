var currentRequest = null;

( function ( $ ) {

	"use strict";

	window.BP_Repost = {

		init: function() {

			this.bp_repost();

		},

		bp_repost: function() {

			$( document ).on( 'click', '.bp-repost-activity', function( e ){

				e.preventDefault();

				var data = {
					action               : 'post_update',
					cookie               : bp_get_cookies(),
					_wpnonce_post_update : $('#_wpnonce_post_update').val(),
					object               : '',
					content              : '&nbsp;',
					item_id              : '',
					secondary_item_id    : $( this ).data( 'activity_id' ),
					_bp_as_nonce         : $('#_bp_as_nonce').val() || ''
				}

				// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
				var currentRequest = $.ajax({
					type : 'post',
					url  : ajaxurl,
					data : data,
					beforeSend : function() {
						if( currentRequest != null ) {

							currentRequest.abort();

						}
					},
					success : function( response ) {

						alert(response);

					}

				});

			});

		},

	};

	$( document ).on( 'ready', function () {

		BP_Repost.init();

	});


} )( jQuery );