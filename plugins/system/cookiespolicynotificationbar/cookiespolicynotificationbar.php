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

defined('_JEXEC') or die;

class plgSystemCookiesPolicyNotificationBar extends JPlugin
{
	// joomla vars
	var $user = '';
	var $host = '';
	var $app = '';
	
	// get vars from url
	var $delete_cookiesDirective = '';
	
	// get params
	var $position = '';
	var $duration = '';
	var $animate_duration = '';
	var $limit = '';
	var $header_message = '';
	var $buttonText = '';
	var $buttonMoreText = '';
	var $buttonMoreLink = '';
	var $enable_google_fonts_for_message = '';
	var $google_font_family_for_message = '';
	var $enable_google_fonts_for_buttons = '';
	var $google_font_family_for_buttons = '';
	var $btn_font_size = '';
	var $btn_border_radius = '';
	var $ok_btn_normal_font_color = '';
	var $ok_btn_hover_font_color = '';
	var $ok_btn_normal_bg_color = '';
	var $ok_btn_hover_bg_color = '';
	var $more_btn_normal_font_color = '';
	var $more_btn_hover_font_color = '';
	var $more_btn_normal_bg_color = '';
	var $more_btn_hover_bg_color = '';
	var $fontColor = '';
	var $linkColor = '';
	var $fontSize = '';
	var $backgroundOpacity = '';
	var $backgroundColor = '';
	var $center_alignment = '';
	var $height = '';
	var $line_height = '';
	var $cookie_name = '';
	var $show_in_iframes = '';
	var $custom_css = '';
	var $loadjQuery = '';
	var $inlude_menu_items = '';
	var $exclude_menu_items = '';
	var $debug_mode = '';
	var $always_display = '';
	var $blockCookies = '';
	var $blockCookiesText = '';
	
	public function __construct(&$subject, $config)
	{
		// joomla vars
		jimport('joomla.environment.uri' );
		$this->user = JFactory::getUser();
		$this->host = JURI::root(true);
		$this->app = JFactory::getApplication();
				
		// get vars from url
		$this->delete_cookiesDirective = JRequest::getVar('delete_cookiesDirective', '0'); // 1: enabled, 0: disabled

		// get plugin params
		$db = JFactory::getDBO();	
		$db->setQuery("SELECT params FROM #__extensions WHERE element = 'cookiespolicynotificationbar' AND folder = 'system'");
		$this->_plugin = $db->loadObject();
		$this->_params = new JRegistry();
		$this->_params->loadString($this->_plugin->params);
	
		// get params
		$this->position = $this->_params->get('position', 'top');
		$this->duration = $this->_params->get('duration', '60');
		$this->animate_duration = $this->_params->get('animate_duration', '2000');
		$this->limit = $this->_params->get('limit', '0');	
		$this->enable_google_fonts_for_message = $this->_params->get('enable_google_fonts_for_message', '1');
		$this->google_font_family_for_message = $this->_params->get('google_font_family_for_message', 'Lato');
		$this->enable_google_fonts_for_buttons = $this->_params->get('enable_google_fonts_for_buttons', '1');
		$this->google_font_family_for_buttons = $this->_params->get('google_font_family_for_buttons', 'Lato');
		$this->btn_font_size = $this->_params->get('btn_font_size', '12px');
		$this->btn_border_radius = $this->_params->get('btn_border_radius', '4px');
		$this->ok_btn_normal_font_color = $this->_params->get('ok_btn_normal_font_color', '#FFFFFF');
		$this->ok_btn_hover_font_color = $this->_params->get('ok_btn_hover_font_color', '#FFFFFF');
		$this->ok_btn_normal_bg_color = $this->_params->get('ok_btn_normal_bg_color', '#3B89C7');
		$this->ok_btn_hover_bg_color = $this->_params->get('ok_btn_hover_bg_color', '#3176AF');
		$this->more_btn_normal_font_color = $this->_params->get('more_btn_normal_font_color', '#FFFFFF');
		$this->more_btn_hover_font_color = $this->_params->get('more_btn_hover_font_color', '#FFFFFF');
		$this->more_btn_normal_bg_color = $this->_params->get('more_btn_normal_bg_color', '#7B8A8B');
		$this->more_btn_hover_bg_color = $this->_params->get('more_btn_hover_bg_color', '#697677');
		$this->fontColor = $this->_params->get('fontColor', '#F1F1F3');
		$this->linkColor = $this->_params->get('linkColor', '#FFFFFF');
		$this->fontSize = $this->_params->get('fontSize', '12px');
		$this->center_alignment = $this->_params->get('center_alignment', '0');
		$this->backgroundOpacity = $this->_params->get('backgroundOpacity', '95');
		$this->backgroundColor = $this->_params->get('backgroundColor', '#323A45');
		$this->custom_css = $this->_params->get('custom_css', '');
		$this->height = $this->_params->get('height', '');
		$this->line_height = $this->_params->get('line_height', '');
		$this->cookie_name = $this->_params->get('cookie_name', 'cookiesDirective');
		$this->show_in_iframes = $this->_params->get('show_in_iframes', '0');
		$this->loadjQuery = $this->_params->get('loadjQuery', '1');
		$this->inlude_menu_items = $this->_params->get('inlude_menu_items', '');
		$this->exclude_menu_items = $this->_params->get('exclude_menu_items', '');
		$this->debug_mode = $this->_params->get('debug_mode', '0');
		$this->always_display = $this->_params->get('always_display', '0');
		$this->blockCookies = $this->_params->get('blockCookies', '0');	
		$this->blockCookiesText = $this->_params->get('blockCookiesText', '0');	
		$this->download_id = $this->_params->get('download_id', '');

		parent::__construct($subject, $config);
	}

	function blockCookies()
	{
		// Block Cookies (If the User does not accept the cookies policy, block all the cookies of this website.)
		if ($this->app->isSite() && (($this->blockCookies || ($this->debug_mode && $this->delete_cookiesDirective)) && !$this->always_display)):
			$page = JResponse::GetBody();

			// Get content from cpnb tags <cpnb>google adsense code goes here...<cpnb>
			preg_match_all("'<cpnb>(.*?)</cpnb>'si", $page, $matches);
			$matches_arr = array_filter($matches[0]);

			$code_arr = array();
			if(!empty($matches_arr)):
				for ($i=0; $i<count($matches_arr);$i++):
					if (isset($matches_arr[$i])):
						$code_arr[] = $matches_arr[$i];
					endif;
				endfor;
			endif;

			if (!array_key_exists($this->cookie_name, $_COOKIE)): // check if the cookie name exists in cookies. if not block cookies
				if (!empty($code_arr)):
					foreach ($code_arr as $k=>$v): 
						$page = str_replace($v, ($this->blockCookiesText ? JText::_('J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_COOKIES_BLOCKED') : ''), $page);
					endforeach;
				endif;
			endif;

			JResponse::SetBody($page);
		endif;
	}

	function onAfterRender()
	{ 
		// block cookies
		return $this->blockCookies();
	}

	function onAfterDispatch()
	{
		$document = JFactory::getDocument();

		// get plugin's id from db
		$db = JFactory::getDBO();	
		$query = "SELECT extension_id FROM #__extensions WHERE type='plugin' AND element = 'cookiespolicynotificationbar'";
		$db->setQuery($query);
		$db->query();
		$extension_id = $db->loadResult();

		// get plugin's id from browser
		$get_extension_id = JRequest::getVar('extension_id'); 
		$layout = JRequest::getVar('layout'); 
		$view = JRequest::getVar('view');

		// BEGIN: Only for back-end
		if ($this->app->isAdmin() && $layout == 'edit' && $view == 'plugin' && $get_extension_id == $extension_id):
			
			// get plugin params
			$yt = JRequest::getVar('yt');
			if ($yt == 'debugging')
			{
				$db = JFactory::getDBO();	
				$db->setQuery("SELECT params FROM #__extensions WHERE element = 'cookiespolicynotificationbar' AND folder = 'system'");
				$plg_params = $db->loadObject();
				$plg_params_arr = json_decode($plg_params->params);
				?><pre><?php print_r($plg_params_arr); ?></pre><?php
			}
			
			// Load CSS and JS for the fontselect jquery plugin
			$document->addStyleSheet($this->host.'/plugins/system/cookiespolicynotificationbar/assets/fontselect-jquery-plugin/fontselect.css');
			$document->addScript($this->host.'/plugins/system/cookiespolicynotificationbar/assets/fontselect-jquery-plugin/jquery.fontselect.min.js');
			$fontselect_js_code = "
			jQuery(function(){
				jQuery('#jform_params_google_font_family_for_message').fontselect();
				jQuery('#jform_params_google_font_family_for_buttons').fontselect();
			});";
			$document->addScriptDeclaration($fontselect_js_code);
			
			// add some inline css (hide the language tabs from the older version, but do not remove them)
			$inline_css_style = "ul#myTabTabs li:nth-of-type(1n+8), div#myTabContent div.tab-pane:nth-of-type(1n+8), div.pane-sliders div.panel:nth-of-type(1n+8) { display: none !important; }";
			$document->addStyleDeclaration($inline_css_style, 'text/css');
			
			// Check if Download ID has been specified
			if (empty($this->download_id) || $this->download_id == ''): // check also if is validated?
				$download_id_required_msg = JText::_('<p>You need to specify your <strong>Download ID</strong> before you can receive updates for <strong>Cookies Policy Notification Bar PRO</strong>. For more information please follow our instructions <a href="https://www.web357.eu/download-id" target="_blank"><strong>here</strong></a>.</p>');
				JFactory::getApplication()->enqueueMessage($download_id_required_msg, 'warning');
			endif;
			
		endif;
		// END: Only for back-end
						
		// BEGIN: Loading plugin language file
		$lang = JFactory::getLanguage();
		$current_lang_tag = $lang->getTag();
		$lang = JFactory::getLanguage();
		$extension = 'plg_system_cookiespolicynotificationbar';
		$base_dir = JPATH_SITE.'/plugins/system/cookiespolicynotificationbar/';
		$language_tag = (!empty($current_lang_tag)) ? $current_lang_tag : 'en-GB';
		$reload = true;
		$lang->load($extension, $base_dir, $language_tag, $reload);
		// END: Loading plugin language file

		// BEGIN: check if the site is offline
		if ($this->checKOffline()): 
			return true; 
		endif;
		// END: check if the site is offline
		
		// BEGIN: Include or Exclude pages
		if ($this->checKIncludeExclude()): 
			return true; 
		endif;
		// END: Include or Exclude pages

		if ($this->app->isSite()) : // for frontend only

			// BEGIN: check if alway display
			if ($this->always_display):
				unset($_COOKIE[$this->cookie_name]);
				setcookie($this->cookie_name, null, -1, '/');
			endif;
			// END: check if alway display
			
			// BEGIN: Block Cookies (If User does not accept cookies policy, block all cookies of website.)
			if (($this->blockCookies || ($this->debug_mode && $this->delete_cookiesDirective)) && !$this->always_display):
				foreach ($_COOKIE as $key=>$val):
					if (isset($_COOKIE[$this->cookie_name])):
						if (!isset($_COOKIE[$this->cookie_name])):
							unset($_COOKIE[$this->cookie_name]);
							setcookie($this->cookie_name, null, -1, '/');
						endif;
					endif;
				endforeach;
			endif;
			// END: Block Cookies
			
			// BEGIN: Debug mode
			if ($this->debug_mode):
	
				// Style (CSS)
				$document->addStyleSheet($this->host.'/plugins/system/cookiespolicynotificationbar/assets/css/style.css');
				$document->addStyleSheetVersion($this->host.'/plugins/system/cookiespolicynotificationbar/assets/css/custom.css');

				// Display message
				$debug_mode_html  = '';
				$debug_mode_html .= '<div class="debug_mode alert alert-info">';
				$debug_mode_html .= '<button type="button" class="close" data-dismiss="alert">&times;</button>';
				$debug_mode_html .= '<h2>Debug mode</h2>';
				
				// Display Debug Message
				$debug_mode_html .= '<h4>Info</h4>';
				if (!isset($_COOKIE[$this->cookie_name])): // if User don't press the accept button yet.
					$debug_mode_html .= '<p style="color: red;">The User hasn\'t accepted the cookies policy yet.<br>The cookies are disabled, till clicks on the "Accept" button.</p>';	
				elseif (isset($_COOKIE[$this->cookie_name])): // if User press the accept button.
					$debug_mode_html .= '<p style="color: red;">The User has clicked on "Accept cookies" button.<br>All the cookies have been enabled.</p>';
				endif;
				
				// Display Cookies
				$debug_mode_html .= '<h4>Display Active Cookies</h4>';
				$debug_mode_html .= '<table border="1" cellpadding="5" cellspacing="5">';
				$debug_mode_html .= '<tr><th>#</th><th>Key</th><th>Value</th></tr>';
				$cookies_num = 0;
				foreach ($_COOKIE as $key=>$val):
					if (isset($_COOKIE[$key])):
						$debug_mode_html .= '<tr><td>'.($cookies_num+1).'</td><td>'.$key.'</td><td>'.$val.'</td></tr>';
					$cookies_num++;
					endif;
				endforeach;
				$debug_mode_html .= '</table>';
				$debug_mode_html .= ($cookies_num == 0) ? '<p style="padding-top: 15px; color: red;">All cookies have been blocked successfully,<br>
by "Cookies Policy Notification bar", J! Plugin.</p>' : '';
				
				// BEGIN: Delete Cookies
					$debug_mode_html .= '<h4>Delete Cookies</h4>';
					
					// useful vars
					JHTML::_('behavior.tooltip');
					$front_page_url = JURI::base().'?delete_cookiesDirective=1';
			
					// display the debugging button
					if (!isset($_COOKIE[$this->cookie_name]) || !$this->delete_cookiesDirective):
						$debug_mode_html .= '<p>';
						$debug_mode_html .= '<span class="editlinktip hasTip" title="'.JText::_('J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_CLEAN_COOKIES_TOOLTIP').'" >';
						$debug_mode_html .= '<a href="'.$front_page_url.'" class="btn btn-refresh"><i class="icon-star"></i> '.JText::_('J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_CLEAN_COOKIES_BTN').'</a> ';
						$debug_mode_html .= '</span>';
						$debug_mode_html .= '</p>';
					endif;
			
					// remove the cookie from browser
					if($this->delete_cookiesDirective && !$this->always_display):
						foreach ($_COOKIE as $key=>$val):
							if (isset($_COOKIE[$key])):
								if (!isset($_COOKIE[$this->cookie_name])):
									unset($_COOKIE[$key]);
									setcookie($key, null, -1, '/');
								endif;
							endif;
						endforeach;
					endif;
				
					// cookie deleted successfully
					if ($this->delete_cookiesDirective):
						$message = '<p><strong>'.JText::_('J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_COOKIE_DELETED').'</strong><br /><em>'.JText::_('J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_REFRESH').' <a href="'.$front_page_url.'" target="_blank">'.$front_page_url.'.</a></em></p>';
						$msg_type = 'notice';
					endif;
				// END: Delete Cookies

				$debug_mode_html .= '</div>';
				echo $debug_mode_html;
			endif;
			// END: Debug mode
				
		endif;
	}
	
	function onBeforeCompileHead()
	{		
		// joomla vars
		$document = JFactory::getDocument();

		// BEGIN: check if the site is offline
		if ($this->checKOffline()): 
			return true; 
		endif;
		// END: check if the site is offline
		 
		// BEGIN: Include or Exclude pages
		if ($this->checKIncludeExclude()): 
			return true; 
		endif;
		// END: Include or Exclude pages
				
		// BEGIN: do not enable the plugin in the popup window
		$cpnb_popup_window = JRequest::getVar('cpnb');
		if ($cpnb_popup_window): 
			return true; 
		endif;
		// END: do not enable the plugin in the popup window

		// for frontend only and if User don't press the accept button yet.
		if ($this->app->isSite() && !isset($_COOKIE[$this->cookie_name])):
			
			// Get language tag
			$language = JFactory::getLanguage();
			$language_tag = str_replace("-", "_", $language->get('tag'));
			$language_tag = !empty($language_tag) ? $language_tag : "en_GB";

			// Style (CSS)
			$document->addStyleSheet($this->host.'/plugins/system/cookiespolicynotificationbar/assets/css/style.css');
			$document->addStyleSheet($this->host.'/plugins/system/cookiespolicynotificationbar/assets/css/custom.css');

			// BEGIN: (Javascript)
			// load jQuery library
			if ($this->loadjQuery):
				$document->addScript($this->host.'/plugins/system/cookiespolicynotificationbar/assets/js/jquery-1.8.3.min.js');
			endif;
			
			// load Cookies Directive
			$document->addScript($this->host.'/plugins/system/cookiespolicynotificationbar/assets/js/jquery.cookiesdirective.js');
			$document->addScript($this->host.'/plugins/system/cookiespolicynotificationbar/assets/js/custom_'.$language_tag.'.js');
	
			// END: (Javascript)
			
			// BEGIN: (Javascript)

			// load jQuery library
			if ($this->loadjQuery):
				$document->addScript($this->host.'/plugins/system/cookiespolicynotificationbar/assets/js/jquery-1.8.3.min.js');
			endif;
			
			// load Cookies Directive
			$document->addScript($this->host.'/plugins/system/cookiespolicynotificationbar/assets/js/jquery.cookiesdirective.js');
			// END: (Javascript)			

		endif;
	}
	
	function checKOffline()
	{
		 // BEGIN: check if the site is offline
		if ($this->app->getCfg('offline') == 1): // site is offline
			if ($this->user->get('id') == 0): // disable if is guest, allow if a User has access in offline website
				return true;
			endif;
		endif;
		 // END: check if the site is offline
	}

	function checKIncludeExclude()
	{
		// BEGIN: Include or Exclude pages
		$itemid = JRequest::getVar('Itemid');
		
		// Include
		$inlude_menu_items = $this->inlude_menu_items;
		if (!empty($inlude_menu_items) && !in_array($itemid, $inlude_menu_items)):
			return true; // exit if the current menu item id is not in the included menu items list.
		endif;
				
		// Exclude
		$exclude_menu_items = $this->exclude_menu_items;
		if (!empty($exclude_menu_items) && in_array($itemid, $exclude_menu_items)):
			return true; // exit if the current menu item id is in the excluded menu items list.
		endif;
		// END: Include or Exclude pages
	}

	public function onInstallerBeforePackageDownload(&$url, &$headers)
	{
		$uri = JUri::getInstance($url);
		
		// I don't care about download URLs not coming from our site
		$host = $uri->getHost();
		if ($host != 'downloads.web357.eu'):
			return true;
		endif;
		
		// fetch download id from extension parameters
		$download_id = $this->download_id;
				
		// Append the Download ID to the download URL
		if (!empty($download_id)):
			$current_url = JURI::getInstance()->toString();
			$parse = parse_url($current_url);
			$domain = isset($parse['host']) ? $parse['host'] : 'domain.com';
			$uri->setVar('liveupdate', 'true');
			$uri->setVar('domain', $domain);
			$uri->setVar('dlid', $download_id);
			$url = $uri->toString();
		endif;
		
		return true;
	}

	public function onContentPrepareForm($form, $data)
	{
		// Check if CPNB plugin exists
		jimport('joomla.plugin.helper');
		if(!JPluginHelper::isEnabled('system', 'cookiespolicynotificationbar')):
			$msg = JText::_('<div style="border:1px solid red; padding:10px; width: 50%"><strong style="color:red;">The plugin is unpublished.</strong><br>The plugin should be enabled to display the input text fields for each of your active languages. Please, enable the plugin first and then try to navigate to this tab again!</div>');
			JFactory::getApplication()->enqueueMessage($msg, 'warning');
			return false;
		endif;

		// Form
		if (!($form instanceof JForm)):
			$this->_subject->setError('JERROR_NOT_A_FORM');
			return false;
		endif;

		// Get language tag
		$language = JFactory::getLanguage();
		$language_tag = str_replace("-", "_", $language->get('tag'));
		$language_tag = !empty($language_tag) ? $language_tag : "en_GB";
		
		// Check form
		$data_name = str_replace('System - ', '', JText::_('PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR'));
		if ($form->getName() != 'com_plugins.plugin' || isset($data->name) && $data->name != 'PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR'):
			return true;
		endif;
		
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

		// BEGIN: (CSS)
		$style  = "";
		$style .= '/* BEGIN: Cookies Policy Notification Bar - J! system plugin (Powered by: Web357.eu) */'."\n";
		
		// Check if google fonts are enabled
		if ($this->enable_google_fonts_for_buttons || $this->enable_google_fonts_for_message):
		
			$style .= '@import url(https://fonts.googleapis.com/css?family='.$this->google_font_family_for_message.');'."\n";
			$style .= ($this->google_font_family_for_message != $this->google_font_family_for_buttons) ? '@import url(https://fonts.googleapis.com/css?family='.$this->google_font_family_for_buttons.');'."\n" : '';
			$style .= '#w357_cpnb {}'."\n";
			$style .= '#w357_cpnb #w357_cpnb_outer {}'."\n";
			$style .= '#w357_cpnb #w357_cpnb_inner {}'."\n";
		
			// message
			$style .= '#w357_cpnb #w357_cpnb_message { font-family: \''.str_replace("+", " ", $this->google_font_family_for_message).'\', \'Helvetica Neue\', Helvetica, Arial, sans-serif !important; }'."\n";
		
			// buttons
			$style .= '#w357_cpnb #w357_cpnb_buttons, #cpnb_modal #w357_cpnb_button_ok_modal { font-family: \''.str_replace("+", " ", $this->google_font_family_for_buttons).'\', \'Helvetica Neue\', Helvetica, Arial, sans-serif !important; }'."\n";
		endif;
		
		$style .= '#w357_cpnb a.w357_cpnb_button, #cpnb_modal a.w357_cpnb_button { -webkit-border-radius: '.$this->btn_border_radius.'; -moz-border-radius: '.$this->btn_border_radius.'; border-radius: '.$this->btn_border_radius.'; font-size: '.$this->btn_font_size.'; }'."\n";
		$style .= '#w357_cpnb a#w357_cpnb_button_ok, #cpnb_modal a#w357_cpnb_button_ok_modal { color: '.$this->ok_btn_normal_font_color.'; background-color: '.$this->ok_btn_normal_bg_color.'; }'."\n";
		$style .= '#w357_cpnb a#w357_cpnb_button_ok:hover, #w357_cpnb a#w357_cpnb_button_ok:focus, #cpnb_modal a#w357_cpnb_button_ok_modal:hover, #cpnb_modal a#w357_cpnb_button_ok_modal:focus { color: '.$this->ok_btn_hover_font_color.'; background-color: '.$this->ok_btn_hover_bg_color.'; }'."\n";
		$style .= '#w357_cpnb a#w357_cpnb_button_more { color: '.$this->more_btn_normal_font_color.'; background-color: '.$this->more_btn_normal_bg_color.'; }'."\n";
		$style .= '#w357_cpnb a#w357_cpnb_button_more:hover, #w357_cpnb a#w357_cpnb_button_more:focus { color: '.$this->more_btn_hover_font_color.'; background-color: '.$this->more_btn_hover_bg_color.'; }'."\n";
		
		// link in message
		$style .= '#w357_cpnb #w357_cpnb_message a { color: '.$this->linkColor.' !important; font-weight: 700; text-decoration: none !important; }'."\n";
		$style .= '#w357_cpnb #w357_cpnb_message a:hover { text-decoration: underline !important; }'."\n";
		
		if ($this->center_alignment):
			$style .= '/* center alignment */'."\n";
			$style .= '#w357_cpnb #w357_cpnb_message { text-align: center; float: none; display: inline-block; }'."\n";
			$style .= '#w357_cpnb #w357_cpnb_buttons { display: inline-block; float: none; margin-left: 20px; }'."\n";
			$style .= '@media (max-width: 1580px) {'."\n";
			$style .= '  #w357_cpnb #w357_cpnb_message { float: none; width: 100%; display: block; clear: both; margin-bottom: 15px; }'."\n";
			$style .= '  #w357_cpnb #w357_cpnb_buttons { float: none; width: 100%; clear: both; text-align: center; margin-top: 0; margin-left: 0; margin-bottom: 10px; right: 0; position: relative; }'."\n";
			$style .= '}'."\n";
		endif;

		// begin: custom css from parameters
		if (!empty($this->custom_css)):
			$style .= '/* custom css */'."\n";
			$style .= $this->custom_css."\n";
		endif;
		// end: custom css from parameters
		
		if (is_array($lang_codes_arr)):
			
			foreach ($lang_codes_arr as $language_tag):

				// get params for each language
				$message_prm = $this->_params->get('header_message_'.$language_tag, JText::_('J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_HEADER_MESSAGE_DEFAULT'));
				$message = preg_replace("/\r\n|\r|\n/",'<br/>', $message_prm);
				$button_text = $this->_params->get('button_text_'.$language_tag, JText::_('J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_BUTTON_DEFAULT_TEXT_VALUE'));
				$more_info_btn = $this->_params->get('more_info_btn_'.$language_tag, '1');
				$button_more_text = $this->_params->get('button_more_text_'.$language_tag, JText::_('J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_BUTTON_MORETEXT_DEFAULT_VALUE'));
				$more_info_btn_type = $this->_params->get('more_info_btn_type_'.$language_tag, 'link');
				$button_more_link = $this->_params->get('button_more_link_'.$language_tag, '');
				$cpnb_modal_menu_item = (int) $this->_params->get('cpnb_modal_menu_item_'.$language_tag, '');
				$link_target = $this->_params->get('link_target_'.$language_tag, '_self');
				$popup_width = $this->_params->get('popup_width_'.$language_tag, '800');
				$popup_height = $this->_params->get('popup_height_'.$language_tag, '600');
				$custom_text = $this->_params->get('custom_text_'.$language_tag, JText::_('J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_CUSTOM_TEXT_DEFAULT'));

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
				if ($this->show_in_iframes == 0):
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
						w357_position: '".$this->position."',
						w357_duration: ".$this->duration.",
						w357_animate_duration: ".$this->animate_duration.",
						w357_limit: ".$this->limit.",
						w357_message: '".addslashes($message)."',
						w357_buttonText: '".addslashes($button_text)."',
						w357_buttonMoreText: '".addslashes($button_more_text)."',
						w357_buttonMoreLink: '".$button_more_destination_url."',
						w357_display_more_info_btn: ".$more_info_btn.",
						w357_fontColor: '".$this->fontColor."',
						w357_linkColor: '".$this->linkColor."',
						w357_fontSize: '".$this->fontSize."',
						w357_backgroundOpacity: '".$this->backgroundOpacity."',
						w357_backgroundColor: '".$this->backgroundColor."',
						w357_height: '".$this->height."',
						w357_line_height: '".$this->line_height."',
						w357_cookie_name: '".$this->cookie_name."',
						w357_link_target: '".$link_target."',
						w357_popup_width: '".$popup_width."',
						w357_popup_height: '".$popup_height."',
						w357_customText: '".addslashes(preg_replace("/[\n\r]/","",$custom_text))."',
						w357_more_info_btn_type: '".$more_info_btn_type."',
					});
				
					".($this->blockCookies || ($this->debug_mode && $this->delete_cookiesDirective) ? "$(document).on('click', '#w357_cpnb_button_ok', function(){ location.reload(true); });" : "")."

				  });
				  
				})(jQuery);\n";
				$js_code .= "// END: Cookies Policy Notification Bar - J! plugin (powered by: web357.eu)";

				// create the JS lang file
				$path_to_js_file = JPATH_SITE."/plugins/system/cookiespolicynotificationbar/assets/js/custom_".$language_tag.".js";
				JFile::write($path_to_js_file, $js_code);
		
			endforeach; 
		
		endif;

		$style .= '/* END: Cookies Policy Notification Bar - J! system plugin (Powered by: Web357.eu) */';
		
		//Import filesystem libraries. Perhaps not necessary, but does not hurt
		jimport('joomla.filesystem.file');

		// begin: write CSS to file
		$path_to_css_file = JPATH_SITE."/plugins/system/cookiespolicynotificationbar/assets/css/custom.css";
		JFile::write($path_to_css_file, $style);
		// end: write CSS to file
						
		return true;
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