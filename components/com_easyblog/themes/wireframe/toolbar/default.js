
EasyBlog.ready(function($){

    if ($('#more-settings li').length == 0) {
        $('#more-settings').parent('li').addClass('hide');
    }

	$(document).on('click', '[data-blog-toolbar-logout]', function(event) {
		$('[data-blog-logout-form]').submit();
	});

    $('.btn-eb-navbar').click(function() {
        $('.eb-navbar-collapse').toggleClass("in");
        return false;
    });

	$('#ezblog-head #ezblog-search').bind('focus', function(){

        $(this).animate({
            width: '170'
        });
	});

	$('#ezblog-head #ezblog-search').bind( 'blur' , function(){
		$(this).animate({ width: '120'});
	});

    <?php if ($this->isMobile()) { ?>
	$('.btn-eb-navbar').click(function() {
		$('.eb-nav-collapse').toggleClass("nav-show");
		return false;
	});

	$('[data-eb-mobile-toolbar-search]').on('click', function() {
		$('[data-eb-mobile-search]').toggleClass('hide');
	});

	$('[data-eb-toolbar-toggle]').on('click', function() {
		var contents = $('[data-eb-mobile-toolbar]').html();

		EasyBlog.dialog({
			"title": "<?php echo JText::_('COM_EASYBLOG_TOOLBAR_MENU_TITLE', true);?>",
			"content": contents
		});
	});

	$('[data-eb-toolbar-dashboard-toggle]').on('click', function() {
		var contents = $('[data-eb-mobile-dashboard-toolbar]').html();

		EasyBlog.dialog({
			"title": "<?php echo JText::_('COM_EASYBLOG_TOOLBAR_DASHBOARD_TITLE', true);?>",
			"content": contents
		});
	});
    <?php } ?>
});
