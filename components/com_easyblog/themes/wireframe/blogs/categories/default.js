
EasyBlog.ready(function($){

	$('[data-more-categories-link]').on('click', function() {
		$(this).hide();
		$('[data-more-categories]').css('display', 'inline');
	});

	$('[data-show-all-authors]').on('click', function() {
		$('[data-author-item]').each(function()
		{
			$(this).find('img').attr('src', $(this).data('src'));

			$(this).removeClass('hide');
		});

		// Hide the button block
		$(this).addClass('hide');
	});
});
