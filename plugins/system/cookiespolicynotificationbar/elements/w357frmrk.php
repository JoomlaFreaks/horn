<?php
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

defined('JPATH_BASE') or die;
		
jimport('joomla.form.formfield');
jimport( 'joomla.form.form' );

class JFormFieldw357frmrk extends JFormField {
	
	protected $type = 'w357frmrk';

	protected function getLabel()
	{
		// BEGIN: Show/Hide parameter field if the parent field is enabled.
		$extra_jquery_code = '<script>
		jQuery(document).ready(function (val) {

			function toggleEnableGoogleFontsForMessageBtn(val) {
				var controls = [\'jform[params][google_font_family_for_message]\'];
				var checked = jQuery(\'input[name="jform[params][enable_google_fonts_for_message]"]:radio:checked\').first();
				if (checked) {
					val = checked.val();
				}
				jQuery.each(controls, function () {
					jQuery(\'[name="\' + this + \'"]\').each(function () {
						var group = jQuery(this).closest(\'.control-group\');
						if (group) {
							if (val == 1) {
								group.show();
							} else {
								group.hide();
							}
						}
					});
				});
			}
			jQuery(\'input[name="jform[params][enable_google_fonts_for_message]"]:radio\').click(function () {
				toggleEnableGoogleFontsForMessageBtn(jQuery(this).val());
			});		
			toggleEnableGoogleFontsForMessageBtn();

			function toggleEnableGoogleFontsForButtonsBtn(val) {
				var controls = [\'jform[params][google_font_family_for_buttons]\'];
				var checked = jQuery(\'input[name="jform[params][enable_google_fonts_for_buttons]"]:radio:checked\').first();
				if (checked) {
					val = checked.val();
				}
				jQuery.each(controls, function () {
					jQuery(\'[name="\' + this + \'"]\').each(function () {
						var group = jQuery(this).closest(\'.control-group\');
						if (group) {
							if (val == 1) {
								group.show();
							} else {
								group.hide();
							}
						}
					});
				});
			}
			jQuery(\'input[name="jform[params][enable_google_fonts_for_buttons]"]:radio\').click(function () {
				toggleEnableGoogleFontsForButtonsBtn(jQuery(this).val());
			});		
			toggleEnableGoogleFontsForButtonsBtn();
			
			function toggleMoreInfoBtn(val) {
				var controls = [\'jform[params][link_target]\'];
				var checked = jQuery(\'input[name="jform[params][display_more_info_btn]"]:radio:checked\').first();
				if (checked) {
					val = checked.val();
				}
				jQuery.each(controls, function () {
					jQuery(\'[name="\' + this + \'"]\').each(function () {
						var group = jQuery(this).closest(\'.control-group\');
						if (group) {
							if (val == 1) {
								group.show();
							} else {
								group.hide();
							}
						}
					});
				});
			}
			jQuery(\'input[name="jform[params][display_more_info_btn]"]:radio\').click(function () {
				toggleMoreInfoBtn(jQuery(this).val());
			});		
			toggleMoreInfoBtn();
			
			function toggleLinkTarget(val) {
				var controls = [\'jform[params][popup_width]\', \'jform[params][popup_height]\'];
				var checked = jQuery(\'input[name="jform[params][link_target]"]:radio:checked\').first();
				if (checked) {
					val = checked.val();
				}
				jQuery.each(controls, function () {
					jQuery(\'[name="\' + this + \'"]\').each(function () {
						var control_group_popup_window_width = jQuery(this).closest(\'.control-group\');
						var control_group_popup_window_height = jQuery(this).closest(\'.control-group\').next(\'.control-group\');
						if (control_group_popup_window_width && control_group_popup_window_height) {
							if (val == "popup") {
								control_group_popup_window_width.show();
								control_group_popup_window_height.show();
							} else {
								control_group_popup_window_width.hide();
								control_group_popup_window_height.hide();
							}
						}

					});
				});
			}
			jQuery(\'input[name="jform[params][link_target]"]:radio\').click(function () {
				toggleLinkTarget(jQuery(this).val());
			});		
			toggleLinkTarget();

		});</script>';
		
		//echo $extra_jquery_code; // >>> This method replaced by showon feature.
		// END: Show/Hide parameter field if the parent field is enabled.
	}

	protected function getInput() 
	{
		// BEGIN: Check if Web357 Framework plugin exists
		jimport('joomla.plugin.helper');
		if(!JPluginHelper::isEnabled('system', 'web357framework')):
			$web357framework_required_msg = JText::_('<p>The <strong>"Web357 Framework"</strong> is required for this extension and must be active. Please, download and install it from <a href="http://downloads.web357.eu/?item=web357framework&type=free">here</a>. It\'s FREE!</p>');
			JFactory::getApplication()->enqueueMessage($web357framework_required_msg, 'warning');
			return false;
		else:
			return '';	
		endif;
		// END: Check if Web357 Framework plugin exists
	}
	
}