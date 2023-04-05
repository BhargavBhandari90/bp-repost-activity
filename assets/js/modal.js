jQuery(document).ready(function ($) {
	const modal = $("#repost-box");

	$(document).on("click", '.bp-repost-activity', function () {
		modal.show();
	});

	$(document).on("click", ".close", function () {
		console.log(RE_Post_Activity.theme_package_id);
		$('#repost-activity-form #original_item_id').val('');
        $('#repost-activity-form #posting_at').val('');
		modal.hide();
	});

	$(document).on("click", "#bprpa-close-modal", function () {
		console.log(RE_Post_Activity.theme_package_id);
		$('#repost-activity-form #original_item_id').val('');
        $('#repost-activity-form #posting_at').val('');
		modal.hide();
	});
});
