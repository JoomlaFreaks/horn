
<?php if (JRequest::getVar('layout') != 'composer') { ?>
EasyBlog.require()
.done(function($){
    
    $('[data-dashboard-sign-out]').on('click', function(){ 
        $('[data-dashboard-sign-out-form]').submit();
    });
});
<?php } ?>