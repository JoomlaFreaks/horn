EasyBlog.require()
.script('site/posts/posts')
.done(function($) {

	<?php if ($preview) { ?>
		// prevent all anchor from click when this is a preview page.
		$("a:not([data-preview-toolbar] a, [data-blog-preview-userevision])").prop('onclick', null);

		$("a:not([data-preview-toolbar] a, [data-blog-preview-userevision])").click(function (e) {
			e.preventDefault();
			e.stopPropagation();
		});

	<?php } ?>

	// Implement post library
	$('[data-blog-post]').implement(EasyBlog.Controller.Posts, {
		"ratings": <?php echo $this->config->get('main_ratings') ? 'true' : 'false';?>
	});
});
