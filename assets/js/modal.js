jQuery(document).ready(function ($) {
	const modal = $("#repost-box");

	$(document).on("click", '.bp-repost-activity', function () {
		modal.show();
	});

	$(document).on("click", ".close", function () {
		$('#repost-activity-form #original_item_id').val('');
        $('#repost-activity-form #posting_at').val('');
		modal.hide();
		$('#rpa_group_id').hide();
	});

	$(document).on("click", "#bprpa-close-modal", function () {
		$('#repost-activity-form #original_item_id').val('');
        $('#repost-activity-form #posting_at').val('');
		modal.hide();
		$('#rpa_group_id').hide();
	});
});
