/* ======================================================
# Cookies Policy Notification Bar - Joomla! Plugin v3.2.5 (PRO version)
# -------------------------------------------------------
# For Joomla! 3.x
# Author: Yiannis Christodoulou (yiannis@web357.eu)
# Copyright (©) 2009-2018 Web357. All rights reserved.
# License: GNU/GPLv3, http://www.gnu.org/licenses/gpl-3.0.html
# Website: https://www.web357.eu/
# Demo: http://demo.web357.eu/?item=cookiespolicynotificationbar
# Support: support@web357.eu
# Last modified: 28 Feb 2018, 17:31:50
========================================================= */

// BEGIN: Cookies Policy Notification Bar - J! plugin (powered by: web357.eu)
				jQuery.noConflict();
				(function($){
				  $(window).load(function(){

// hide in iFrames
if (top != self) {
   return false;
}

					// Cookie setting script wrapper
					var cookieScripts = function () {
						// Internal javascript called
						console.log("Running");
					
						// Loading external javascript file
						$.cookiesDirective.loadScript({
							uri:'external.js',
							appendTo: 'body'
						});
					}
					
					$.cookiesDirective({
						w357_explicitConsent: false,
						w357_position: 'top',
						w357_duration: 60,
						w357_animate_duration: 2000,
						w357_limit: 0,
						w357_message: '123',
						w357_buttonText: 'Ok, I\'ve understood!',
						w357_buttonMoreText: 'More Info',
						w357_buttonMoreLink: 'cookies-policy',
						w357_display_more_info_btn: 0,
						w357_fontColor: '#f1f1f3',
						w357_linkColor: '#ffffff',
						w357_fontSize: '12px',
						w357_backgroundOpacity: '95',
						w357_backgroundColor: '#323a45',
						w357_height: 'auto',
						w357_line_height: '',
						w357_cookie_name: 'cookiesDirectiveWeb357',
						w357_link_target: '_self',
						w357_popup_width: '800',
						w357_popup_height: '600',
						w357_customText: '<h1>Cookies Policy</h1><h3>General Use</h3><p>We use cookies, tracking pixels and related technologies on our website. Cookies are small data files that are served by our platform and stored on your device. Our site uses cookies dropped by us or third parties for a variety of purposes including to operate and personalize the website. Also, cookies may also be used to track how you use the site to target ads to you on other websites.</p><h3>Third Parties</h3><p>Our website employs the use the various third-party services. Through the use of our website, these services may place anonymous cookies on the Visitor\'s browser and may send their own cookies to the Visitor\'s cookie file. Some of these services include but are not limited to: Google, Facebook, Twitter, Adroll, MailChimp, Sucuri, Intercom and other social networks, advertising agencies, security firewalls, analytics companies and service providers. These services may also collect and use anonymous identifiers such as IP Address, HTTP Referrer, Unique Device Identifier and other non-personally identifiable information and server logs.</p>',
						w357_more_info_btn_type: 'link',
					});

				  });
				  
				})(jQuery);
// END: Cookies Policy Notification Bar - J! plugin (powered by: web357.eu)