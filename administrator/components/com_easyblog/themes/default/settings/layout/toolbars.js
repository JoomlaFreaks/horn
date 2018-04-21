
EasyBlog.ready(function($) {

	$('[data-layout-toolbar]').on('change', function() {
		var enabled = $(this).val() == 1;
		var toolbarSettings = $('[data-layout-toolbar-items]');

		if (enabled) {
			toolbarSettings.removeClass('hide');
			return;
		}

		toolbarSettings.addClass('hide');
	});
});
