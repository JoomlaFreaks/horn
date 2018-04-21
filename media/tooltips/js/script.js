/**
 * @package         Tooltips
 * @version         7.2.1
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2018 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

var RegularLabsTooltips = null;

(function($) {
	"use strict";

	RegularLabsTooltips = {
		options   : {},
		timeout   : null,
		timeoutOff: false,

		init: function(options) {
			var self = this;

			options      = options ? options : this.getOptions();
			this.options = options;

			// hover mode
			$('.rl_tooltips-link.hover').popover({
				trigger  : 'hover',
				container: 'body',
			});


			// close all popovers on click outside
			$('html').click(function() {
				$('.rl_tooltips-link').popover('hide');
			});

			// do stuff differently for touchscreens
			$('html').one('touchstart', function() {
				// add click mode for hovers
				$('.rl_tooltips-link.hover').popover({
					trigger  : 'manual',
					container: 'body'
				}).click(function(evt) {
					self.show($(this), evt, 'click');
				});
			});

			// close all popovers on click outside
			$('html').on('touchstart', function(e) {
				if ($(e.target).closest('.rl_tooltips').length) {
					return;
				}

				$('.rl_tooltips-link').popover('hide');
			});

			$('.rl_tooltips-link').on('touchstart', function(evt) {
				// prevent click close event
				evt.stopPropagation();
			});

		},

		show: function(el, event, classname) {
			var self = this;

			// prevent other click events
			event.stopPropagation();

			clearTimeout(this.timeout);

			var popover = typeof el.data('popover') !== 'undefined' ? el.data('popover') : el.data('bs.popover');

			// close all other popovers
			$('.rl_tooltips-link.' + classname).each(function() {
				var popover2 = typeof $(this).data('popover') !== 'undefined' ? $(this).data('popover') : $(this).data('bs.popover');

				if (popover2 != popover) {
					$(this).popover('hide');
				}
			});

			// open current
			if (!popover.tip().hasClass('in')) {
				el.popover('show');
			}

			$('.rl_tooltips')
				.click(function(evt) {
					// prevent click close event on popover
					evt.stopPropagation();

					// switch timeout off for this tooltip
					self.timeoutOff = true;
					clearTimeout(self.timeout);
				})
			;
		},


		getOptions: function() {
			return {};
		}
	};

	$(document).ready(function() {
		RegularLabsTooltips.init();
	});
})(jQuery);
