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

class JFormFieldLangmigration extends JFormField {
	
	protected $type = 'Langmigration';

	protected function getLabel()
	{
		return JText::_('Language Migration Tool');	
	}

	protected function getInput()
	{	
		// useful vars
		JHTML::_('behavior.tooltip');
		$html = '';
		$current_url = JURI::getInstance()->toString();
		$current_url_redirect = str_replace('&langmigration=1', '', $current_url);
		$front_page_url = str_replace('administrator/', '', JURI::base());
		$langmigration = JRequest::getVar('langmigration', '0'); // 1: enabled, 0: disabled

		// display the debugging button
		if (!$langmigration):
			$html .= '<p>In case you missed the old strings after upgrade.</p>';
			$html .= '<p>';
			$html .= '<a href="'.$current_url.'&langmigration=1" class="btn btn-refresh"><i class="icon-copy"></i> '.JText::_('Copy your old language strings to the new text fields.').'</a> ';
			$html .= '</p>';
		endif;

		// old lang strings have been moved successfully
		if ($langmigration):

			// get plugin params
			$db = JFactory::getDBO();	
			$db->setQuery("SELECT params FROM #__extensions WHERE element = 'cookiespolicynotificationbar' AND folder = 'system'");
			$plugin = $db->loadObject();
			$params = new JRegistry();
			$params->loadString($plugin->params);
			
			// get params
			$position = $params->get('position', 'top');
			$duration = $params->get('duration', '60');
			$animate_duration = $params->get('animate_duration', '2000');
			$limit = $params->get('limit', '0');	
			$enable_google_fonts_for_message = $params->get('enable_google_fonts_for_message', '1');
			$google_font_family_for_message = $params->get('google_font_family_for_message', 'Lato');
			$enable_google_fonts_for_buttons = $params->get('enable_google_fonts_for_buttons', '1');
			$google_font_family_for_buttons = $params->get('google_font_family_for_buttons', 'Lato');
			$btn_font_size = $params->get('btn_font_size', '12px');
			$btn_border_radius = $params->get('btn_border_radius', '4px');
			$ok_btn_normal_font_color = $params->get('ok_btn_normal_font_color', '#FFFFFF');
			$ok_btn_hover_font_color = $params->get('ok_btn_hover_font_color', '#FFFFFF');
			$ok_btn_normal_bg_color = $params->get('ok_btn_normal_bg_color', '#3B89C7');
			$ok_btn_hover_bg_color = $params->get('ok_btn_hover_bg_color', '#3176AF');
			$more_btn_normal_font_color = $params->get('more_btn_normal_font_color', '#FFFFFF');
			$more_btn_hover_font_color = $params->get('more_btn_hover_font_color', '#FFFFFF');
			$more_btn_normal_bg_color = $params->get('more_btn_normal_bg_color', '#7B8A8B');
			$more_btn_hover_bg_color = $params->get('more_btn_hover_bg_color', '#697677');
			$display_more_info_btn = $params->get('display_more_info_btn', '1');
			$fontColor = $params->get('fontColor', '#F1F1F3');
			$linkColor = $params->get('linkColor', '#FFFFFF');
			$fontSize = $params->get('fontSize', '12px');
			$center_alignment = $params->get('center_alignment', '0');
			$backgroundOpacity = $params->get('backgroundOpacity', '95');
			$backgroundColor = $params->get('backgroundColor', '#323A45');
			$custom_css = $params->get('custom_css', '');
			$height = $params->get('height', '');
			$line_height = $params->get('line_height', '');
			$cookie_name = $params->get('cookie_name', 'cookiesDirective');
			$show_in_iframes = $params->get('show_in_iframes', '0');
			$link_target = $params->get('link_target', '_self');
			$popup_width = $params->get('popup_width', '800');
			$popup_height = $params->get('popup_height', '600');
			$loadjQuery = $params->get('loadjQuery', '1');
			$inlude_menu_items = $params->get('inlude_menu_items', '');
			$exclude_menu_items = $params->get('exclude_menu_items', '');
			$debug_mode = $params->get('debug_mode', '0');
			$always_display = $params->get('always_display', '0');
			$blockCookies = $params->get('blockCookies', '0');	
			$blockCookiesText = $params->get('blockCookiesText', '0');	
			$download_id = $params->get('download_id', '');

			// Get language tag
			$language = JFactory::getLanguage();
			$language_tag = str_replace("-", "_", $language->get('tag'));
			$language_tag = !empty($language_tag) ? $language_tag : "en_GB";

			// Get languages and load form
			$lang_codes_arr = array();
			jimport( 'joomla.language.helper' );
			$languages = JLanguageHelper::getLanguages();
			if (!empty($languages) && count($languages) > 1):
				// Get language details
				foreach ($languages as $tag => $language):
					
					// get language name
					$language_name = $language->title_native;
					$language->lang_code = str_replace('-', '_', $language->lang_code);
					$lang_codes_arr[] = $language->lang_code;

				endforeach;
			else:
				// Get language details
				$language = JFactory::getLanguage();
				$frontend_language_tag = JComponentHelper::getParams('com_languages')->get('site');
				$frontend_language_default_tag = $frontend_language_tag;
				$frontend_language_tag = str_replace("-", "_", $frontend_language_tag);
				$frontend_language_tag = !empty($frontend_language_tag) ? $frontend_language_tag : "en_GB";
				$lang = new stdClass();
				$lang->known_languages = JFactory::getLanguage()->getKnownLanguages();
				$known_lang_name = $lang->known_languages[$frontend_language_default_tag]['name'];
				$known_lang_tag = $lang->known_languages[$frontend_language_default_tag]['tag'];
				$known_lang_name = !empty($known_lang_name) ? $known_lang_name : 'English';
				$known_lang_tag = !empty($known_lang_tag) ? $known_lang_tag : 'en-GB';
				$frontend_language_tag = !empty($frontend_language_tag) ? $frontend_language_tag : $known_lang_tag;
				$language_name = $this->getLanguageNameByTag($frontend_language_default_tag); 
				$language_name = !empty($language_name) ? str_replace(' ('.str_replace('_', '-',$language_tag).')', '', $language_name) : $known_lang_name;
				$lang_codes_arr[] = $frontend_language_tag;

			endif;	

			// get plugin params
			$db = JFactory::getDBO();	
			$db->setQuery("SELECT params FROM #__extensions WHERE element = 'cookiespolicynotificationbar' AND folder = 'system'");
			$plg_params = $db->loadObject();
			$plg_params_arr = json_decode($plg_params->params);

			// build the new textsforlanguages (take the old values and assign to the new variables)
			if (!empty($plg_params_arr->textsforlanguages)):
				$textsforlanguages_obj = new stdClass();
				if (is_array($lang_codes_arr)):
					foreach ($lang_codes_arr as $language_tag):

						if (isset($plg_params_arr->textsforlanguages->{'header_message_'.$language_tag}))
						{
							$textsforlanguages_obj->{'header_message_'.$language_tag} = $plg_params_arr->textsforlanguages->{'header_message_'.$language_tag};
						}

						if (isset($plg_params_arr->textsforlanguages->{'button_text_'.$language_tag}))
						{
							$textsforlanguages_obj->{'button_text_'.$language_tag} = $plg_params_arr->textsforlanguages->{'button_text_'.$language_tag};
						}

						if (isset($plg_params_arr->textsforlanguages->{'button_more_text_'.$language_tag}))
						{
							$textsforlanguages_obj->{'button_more_text_'.$language_tag} = $plg_params_arr->textsforlanguages->{'button_more_text_'.$language_tag};
						}

						if (isset($plg_params_arr->textsforlanguages->{'button_more_link_'.$language_tag}))
						{
							$textsforlanguages_obj->{'button_more_link_'.$language_tag} = $plg_params_arr->textsforlanguages->{'button_more_link_'.$language_tag};
						}

						if (isset($plg_params_arr->textsforlanguages))
						{
							$textsforlanguages_obj->textsforlanguages = $plg_params_arr->textsforlanguages;
						}

					endforeach;
				endif;
			endif;

			$new_arr = new stdClass();
			foreach ($plg_params_arr as $i=>$item)
			{
				if ($i == 'textsforlanguages')
				{
					$new_arr->{'textsforlanguages'} = (!empty($textsforlanguages_obj)) ? $textsforlanguages_obj : $plg_params_arr->textsforlanguages;
				}
				else
				{
					$new_arr->{$i} = $plg_params_arr->{$i};
					// build the new object array with old vars for upgrade purposes
					if (!empty($plg_params_arr->textsforlanguages)):
						if (is_array($lang_codes_arr)):
							foreach ($lang_codes_arr as $language_tag):

								if (isset($plg_params_arr->textsforlanguages))
								{
									$new_arr->textsforlanguages = $plg_params_arr->textsforlanguages;
								}

								if (isset($plg_params_arr->textsforlanguages->{'header_message_'.$language_tag}))
								{
									$new_arr->{'header_message_'.$language_tag} = $plg_params_arr->textsforlanguages->{'header_message_'.$language_tag};
								}
		
								if (isset($plg_params_arr->textsforlanguages->{'button_text_'.$language_tag}))
								{
									$new_arr->{'button_text_'.$language_tag} = $plg_params_arr->textsforlanguages->{'button_text_'.$language_tag};
								}
		
								if (isset($plg_params_arr->textsforlanguages->{'button_more_text_'.$language_tag}))
								{
									$new_arr->{'button_more_text_'.$language_tag} = $plg_params_arr->textsforlanguages->{'button_more_text_'.$language_tag};
								}
		
								if (isset($plg_params_arr->textsforlanguages->{'button_more_link_'.$language_tag}))
								{
									$new_arr->{'button_more_link_'.$language_tag} = $plg_params_arr->textsforlanguages->{'button_more_link_'.$language_tag};
								}
								
							endforeach;
						endif;
					endif;
				}
			}

			$new_params_json = json_encode( $new_arr );

			// store plugin params
			$admin_extension = new stdClass();
			$admin_extension->element = 'cookiespolicynotificationbar';
			$admin_extension->params = json_encode($new_arr); 
			
			$db = JFactory::getDbo();
			try {
				$query = $db->getQuery(true);
				JFactory::getDbo()->updateObject('#__extensions', $admin_extension, 'element');
			}
			catch (RuntimeException $e)
			{
				JError::raiseError(500, $e->getMessage());
				return false;
			}

			if (is_array($lang_codes_arr)):
				
				foreach ($lang_codes_arr as $language_tag):
					
					// get params for each language
					$message_prm = $params->get('header_message_'.$language_tag, JText::_('J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_HEADER_MESSAGE_DEFAULT'));
					$message = preg_replace("/\r\n|\r|\n/",'<br/>', $message_prm);
					$button_text = $params->get('button_text_'.$language_tag, JText::_('J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_BUTTON_DEFAULT_TEXT_VALUE'));
					$more_info_btn = $params->get('more_info_btn_'.$language_tag, '1');
					$button_more_text = $params->get('button_more_text_'.$language_tag, JText::_('J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_BUTTON_MORETEXT_DEFAULT_VALUE'));
					$more_info_btn_type = $params->get('more_info_btn_type_'.$language_tag, 'link');
					$button_more_link = $params->get('button_more_link_'.$language_tag, '');
					$cpnb_modal_menu_item = (int) $params->get('cpnb_modal_menu_item_'.$language_tag, '');
					$link_target = $params->get('link_target_'.$language_tag, '_self');
					$popup_width = $params->get('popup_width_'.$language_tag, '800');
					$popup_height = $params->get('popup_height_'.$language_tag, '600');
					$custom_text = $params->get('custom_text_'.$language_tag, JText::_('J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_CUSTOM_TEXT_DEFAULT'));
	
					// build link for menu item option
					if ($cpnb_modal_menu_item > 0 && $more_info_btn_type == 'menu_item')
					{
						$app = JApplication::getInstance('site');
						$router = $app->getRouter();
						$url = $router->build('index.php?Itemid='.$cpnb_modal_menu_item);
						$url = $url->toString();
						$button_more_destination_url = str_replace('/administrator', '', $url);
					}
					elseif ($more_info_btn_type == 'link')
					{
						$button_more_destination_url = $button_more_link;
					}
					else
					{
						$button_more_destination_url = '';
					}
	
					// js code
					$js_code  = "";
					$js_code .= "// BEGIN: Cookies Policy Notification Bar - J! plugin (powered by: web357.eu)";
					
					// jquery code
					$js_code .= "
					jQuery.noConflict();
					(function($){
					  $(window).load(function(){"."\n\n";
					  
					// Do not show the plugin in iFrames (e.g. modal popups)
					if ($show_in_iframes == 0):
						$js_code .= "// hide in iFrames"."\n";
						$js_code .= "if (top != self) {"."\n";
						$js_code .= "   return false;"."\n";
						$js_code .= "}"."\n\n";
					endif;
								
					$js_code .= "
						// Cookie setting script wrapper
						var cookieScripts = function () {
							// Internal javascript called
							console.log(\"Running\");
						
							// Loading external javascript file
							$.cookiesDirective.loadScript({
								uri:'external.js',
								appendTo: 'body'
							});
						}
						
						$.cookiesDirective({
							w357_explicitConsent: false,
							w357_position: '".$position."',
							w357_duration: ".$duration.",
							w357_animate_duration: ".$animate_duration.",
							w357_limit: ".$limit.",
							w357_message: '".addslashes($message)."',
							w357_buttonText: '".addslashes($button_text)."',
							w357_buttonMoreText: '".addslashes($button_more_text)."',
							w357_buttonMoreLink: '".$button_more_destination_url."',
							w357_display_more_info_btn: ".$more_info_btn.",
							w357_fontColor: '".$fontColor."',
							w357_linkColor: '".$linkColor."',
							w357_fontSize: '".$fontSize."',
							w357_backgroundOpacity: '".$backgroundOpacity."',
							w357_backgroundColor: '".$backgroundColor."',
							w357_height: '".$height."',
							w357_line_height: '".$line_height."',
							w357_cookie_name: '".$cookie_name."',
							w357_link_target: '".$link_target."',
							w357_popup_width: '".$popup_width."',
							w357_popup_height: '".$popup_height."',
							w357_customText: '".addslashes(preg_replace("/[\n\r]/","",$custom_text))."',
							w357_more_info_btn_type: '".$more_info_btn_type."'
						 });
					
						".($blockCookies || ($debug_mode && $delete_cookiesDirective) ? "$(document).on('click', '#w357_cpnb_button_ok', function(){ location.reload(true); });" : "")."
	
					  });
					  
					})(jQuery);\n";
					$js_code .= "// END: Cookies Policy Notification Bar - J! plugin (powered by: web357.eu)";
	
					// create the JS lang file
					$path_to_js_file = JPATH_SITE."/plugins/system/cookiespolicynotificationbar/assets/js/custom_".$language_tag.".js";
					JFile::write($path_to_js_file, $js_code);
			
				endforeach; 
			
			endif;

			// display the success message
			$message = '<p><strong>'.JText::_('The old language strings have been moved successfully. Do not forget to review the new language strings for your language(s) and save the plugin parameters.').'</strong></p>';
			$msg_type = 'notice';
			JFactory::getApplication()->redirect($current_url_redirect, $message, $msg_type, true );
		
		endif;
				
		return $html;		
	}

	public function getDefaultLanguageName()
	{
		$db = JFactory::getDBO();
		$query = "SELECT title_native "
		."FROM #__languages "
		."WHERE published = 1"
		;
		$db->setQuery($query);
		$db->query();

		return $db->loadResult();
	}
	
	public function getLanguageNameByTag($tag)
	{
		$db = JFactory::getDBO();
		$query = "SELECT title_native "
		."FROM #__languages "
		."WHERE lang_code = '".$tag."' AND published = 1"
		;
		$db->setQuery($query);
		$db->query();
		$result = $db->loadResult();
		
		// If there are more than one language
		if (count($result)):
			return $result;
		// If there is only one language
		else:
			return $this->getDefaultLanguageName();
		endif;

	}
}