
EasyBlog.require()
.done(function($){

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

	$('.btn-eb-navbar').click(function() {
		$('.eb-nav-collapse').toggleClass("nav-show");
		return false;
	});

});
