EasyBlog.require()
.script('admin/grid', 'admin/toolbar')
.done(function($) {

	// Insert the dropdown to the toolbar
	$('[data-actions]').implement(EasyBlog.Controller.Admin.Toolbar, {
		"hints": {
			"empty": "<?php echo JText::_('Please select at least 1 post from the table below before submitting the form');?>"
		},
		"bindings": {
			"admin/views/blogs/move": {
				"submit": function() {

					var category = $('#move_category').val();

					if (category == '') {
						return;
					}

					$('[data-move-category]').val(category);

					$.Joomla('submitform', ['blogs.move']);
				}
			},
			"admin/views/blogs/authors": {
				"submit": function() {
					var author = $('#move_author').val();
					$('[data-move-author]').val(author);

					$.Joomla('submitform', ['blogs.changeAuthor']);			
				}
			}
		}
	});

});