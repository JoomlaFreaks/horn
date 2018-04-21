(function($){

// Create recaptcha task
var task = [
	'recaptcha_<?php echo $uid;?>', {
		'sitekey': '<?php echo $key;?>',
		'theme': '<?php echo $color;?>'
	}
];

var runTask = function() {
	grecaptcha.render.apply(grecaptcha, task);
}

// If grecaptcha is not ready, add to task queue
if (!window.grecaptcha) {
	var tasks = window.recaptchaTasks || (window.recaptchaTasks = []);
	tasks.push(task);
// Else run task straightaway
} else {
	runTask(task);
}

// If recaptacha script is not loaded
if (!window.recaptchaScriptLoaded) {

	if (window.JoomlaInitReCaptcha2) {
		// joomla recaptcha already loaded. let ride ontop of JoomlaInitReCaptcha2 callback.
		var joomlaRecaptcha = window.JoomlaInitReCaptcha2;

		// reset
		window.JoomlaInitReCaptcha2 = function() {
			var task;

			// execute our task.
			while (task = tasks.shift()) {
				runTask(task);
			};

			// now we execute joomla callback.
			$(joomlaRecaptcha);
		};
	} else {

		// Load the recaptcha library
		EasyBlog.require()
			.script("//www.google.com/recaptcha/api.js?onload=recaptchaCallback&render=explicit&hl=<?php echo $language;?>");

		window.recaptchaCallback = function() {
			var task;

			while (task = tasks.shift()) {
				runTask(task);
			}
		};

	}

	window.recaptchaScriptLoaded = true;
}

})(jQuery);
