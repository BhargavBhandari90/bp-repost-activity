"use strict";

/* global RE_Post_Activity */
var currentRequest = null;

(function ($) {
  "use strict";

  window.BP_Repost = {
    init: function init() {
      this.bprpa_repost();
      this.bprpa_set_param();
      // this.bprpa_reset_form();
      this.bprpa_show_whereto_post();
    },

    /**
     * Set perameter in ajax request for post update.
     */
    bprpa_set_param: function bprpa_set_param() {
      $.ajaxPrefilter(function (options, originalOptions, jqXHR) {
        // Check if form is available or not.
        if ($('.bp-repost-activity').length == 0) {
          return true;
        } // Modify options, control originalOptions, store jqXHR, etc


        try {
          if (originalOptions.data == null || typeof originalOptions.data === 'undefined' || typeof originalOptions.data.action === 'undefined') {
            return true;
          }
        } catch (e) {
          return true;
        }

        var original_activity_id = $('#repost-activity-form #original_item_id').val(),
          posting_at = $('#repost-activity-form #posting_at').val(),
          group_id = $('#repost-activity-form #rpa_group_id').val(),
          group_args = '',
          new_content = $('#repost-activity-form #repost_comment').val()

        if ('undefined' !== typeof posting_at && 'groups' === posting_at) {
          group_args = '&object=group&item_id=' + group_id;
        } // Set form data into activity ajax.


        if (typeof original_activity_id !== 'undefined' && '' !== original_activity_id && originalOptions.data.action === 'post_update') {
          var repost_content = new_content ? new_content : '';
          options.data += '&original_item_id=' + original_activity_id + '&content=repost&repost_content=' + repost_content + group_args;
        }
      });
      $(document).ajaxComplete(function (event, xhr, settings) {
        // Get ajax data.
        var setting_data = settings.data; // If it's related to spotlight, then run the script.

        if (typeof setting_data !== 'undefined' && setting_data.indexOf('original_item_id') != -1) {
          $('#repost-activity-form #original_item_id').val('');
          $('#repost-activity-form #posting_at').val('');
          $('#repost-activity-form #repost_comment').val('');
          $('#repost-box').hide();
          $('#rpa_group_id').hide();
          const $btn = $('#repost-activity');
          $btn.prop('disabled', false);
          $btn.find('.dashicons').removeClass('spin');
        }
      });
    },
    bprpa_repost: function bprpa_repost() {
      // When we submit repost form.
      $(document).on('submit', '#repost-activity-form', function (e) {
        e.preventDefault();

        if (typeof RE_Post_Activity.theme_package_id === 'undefined') {
          return;
        } // Click if it's legacy.

        const $btn = $('#repost-activity');
        $btn.prop('disabled', true);

        $btn.find('.dashicons')
          .removeClass('dashicons-update')
          .addClass('dashicons-update spin');

        if ('legacy' === RE_Post_Activity.theme_package_id) {
          $('#aw-whats-new-submit').trigger('click');
        } else {
          // Submit, if nouveau.
          $('#whats-new-form').trigger('submit');
        }
      }); // Set data in hidden fields when we click on repost button.

      $(document).on('click', '.bp-repost-activity', function (e) {
        e.preventDefault();
        var activity_id = $(this).data('activity_id'),
          original_content = $('#activity-stream #activity-' + activity_id + ' .activity-inner').html(); // Set values in hidden fields.

        $('#repost-activity-form #original_item_id').val(activity_id); // Show content in popup which we are going to repost.

        $('#repost-activity-form .content').html(original_content);
      });
    },

    /**
     * Reset form when popup is closed.
     */
    bprpa_reset_form: function bprpa_reset_form() {
      $('#repost-box').on('click', '.close', function () {
        $('#repost-activity-form #original_item_id').val('');
        $('#repost-activity-form #posting_at').val('');
      });
    },

    /**
     * Show groups when select group from dropdown.
     */
    bprpa_show_whereto_post: function bprpa_show_whereto_post() {
      $(document).on('change', '#posting_at', function () {
        var posting_at = $(this).val(),
          group_selector = $('#rpa_group_id'); // Display group dropdown if selected group.

        if ('undefined' !== typeof posting_at && 'groups' === posting_at) {
          group_selector.show();
        } else {
          // Hide otherwise.
          group_selector.hide();
        }
      });
    }
  };
  $(document).on('ready', function () {
    BP_Repost.init();
  });
})(jQuery);
