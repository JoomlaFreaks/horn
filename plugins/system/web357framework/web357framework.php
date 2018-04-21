<?php
/* ======================================================
# Web357 Framework - Joomla! System Plugin v1.5.2
# -------------------------------------------------------
# For Joomla! 3.x
# Author: Yiannis Christodoulou (yiannis@web357.eu)
# Copyright (©) 2009-2018 Web357. All rights reserved.
# License: GNU/GPLv3, http://www.gnu.org/licenses/gpl-3.0.html
# Website: https://www.web357.eu/
# Support: support@web357.eu
# Last modified: 28 Feb 2018, 17:31:50
========================================================= */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.event.plugin');
jimport('joomla.plugin.plugin');
jimport( 'joomla.html.parameter' );

if (!class_exists('plgSystemWeb357framework')):
	class plgSystemWeb357framework extends JPlugin
	{
		public function __construct(&$subject, $config)
		{
			parent::__construct($subject, $config);
		}
		
		public function onAfterDispatch()
		{
			jimport('joomla.environment.uri' );
			$host = JURI::root();
			$document = JFactory::getDocument();
			$app = JFactory::getApplication();
			
			// CSS - backend
			if ($app->isAdmin()):
				$document->addStyleSheet($host.'plugins/system/web357framework/assets/css/style.css');
			endif;
		}

		function onContentPrepareForm($form, $data)
		{
			$app    = JFactory::getApplication();
			$option = $app->input->get('option');
			$view 	= $app->input->get('view');
			$layout = $app->input->get('layout');

			if ($app->isAdmin() && $option == 'com_plugins' && $view = 'plugin' && $layout == 'edit')
			{
				if (!($form instanceof JForm))
				{
					$this->_subject->setError('JERROR_NOT_A_FORM');
					return false;
				}
				
				// Get plugin's element
				$db = JFactory::getDbo();
				$query = $db->getQuery(true);
				$query->select($db->quoteName('element'));
				$query->from($db->quoteName('#__extensions'));
				$query->where($db->quoteName('type'). ' = '. $db->quote('plugin'));
				$query->where($db->quoteName('folder'). ' = '. $db->quote('system'));
				$query->where($db->quoteName('extension_id'). ' = '. $app->input->get('extension_id'));
				$query->where($db->quoteName('enabled'). ' = 1');
				$db->setQuery($query);
				$element = $db->loadResult();

				// BEGIN: Cookies Policy Notification Bar - Joomla! Plugin
				if ($element == 'cookiespolicynotificationbar')
				{
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

							// load form
							$this->getLangForm($form, $language_name, $language->lang_code);
							
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

						// load form
						$this->getLangForm($form, $language_name, $frontend_language_tag);
						
					endif; 
				}
				// END: Cookies Policy Notification Bar - Joomla! Plugin

				// BEGIN: Login as User - Joomla! Plugin (add extra fields for user groups and admins)
				if ($element == 'loginasuser')
				{
					// Get User Groups
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query->select('id, title');
					$query->from('#__usergroups');
					$query->where('parent_id > 0');
					$query->order('lft ASC');
					$db->setQuery($query);
					$usergroups = $db->loadObjectList();

					if (!empty($usergroups))
					{
						foreach ($usergroups as $usergroup)
						{
							$this->getUsersFormFieldLoginAsUser($form, $usergroup->id, $usergroup->title);
						}
					}
				}
				// END: Login as User - Joomla! Plugin (add extra fields for user groups and admins)
			}

			return true;
		}

		public function getLangForm($form, $language_name = "English", $lang_code = "en_GB")
		{
			if (isset($form))
			{
				// start building xml file
				$xmlText = '<?xml version="1.0" encoding="utf-8"?>
				<form>
					<fields>
						<fieldset name="texts_for_languages">';

				// HEADER
				$xmlText .= '<field type="langheader" name="header_'.$lang_code.'" class="w357_small_header" addfieldpath="/plugins/system/cookiespolicynotificationbar/elements" lang_code="'.$lang_code.'" language_name="'.$language_name.'" />';
				
				// MESSAGE
				$xmlText .= '<field 
				name="header_message_'.$lang_code.'" 
				type="textarea" 
				default="'.JText::_('J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_HEADER_MESSAGE_DEFAULT').'" label="J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_HEADER_MESSAGE_LBL" 
				description="J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_HEADER_MESSAGE_DESC" 
				rows="6" 
				cols="50" 
				filter="raw" 
				/>';
				
				// BUTTON TEXT
				$xmlText .= '<field 
				name="button_text_'.$lang_code.'" 
				type="text" 
				default="'.JText::_('J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_BUTTON_DEFAULT_TEXT_VALUE').'" 
				label="J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_BUTTON_TEXT_LBL" 
				description="J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_BUTTON_TEXT_DESC" 
				filter="STRING" 
				/>';
				
				// MORE INFO BUTTON
				$xmlText .= '<field 
				name="more_info_btn_'.$lang_code.'" 
				type="radio" 
				class="btn-group btn-group-yesno" 
				default="1" 
				label="J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_MOR_INFO_BTN_LBL" 
				description="J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_MOR_INFO_BTN_DESC">
				<option value="1">JSHOW</option>
				<option value="0">JHIDE</option>
				</field>';

				// BUTTON MORE TEXT
				$xmlText .= '<field 
				name="button_more_text_'.$lang_code.'" 
				type="text" 
				default="'.JText::_('J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_BUTTON_MORETEXT_DEFAULT_VALUE').'" 
				label="J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_BUTTON_MORETEXT_LBL" 
				description="J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_BUTTON_MORETEXT_DESC" 
				filter="STRING" 
				showon="more_info_btn_'.$lang_code.':1" 
				/>';

				// LINK OR Menu Item
				$xmlText .= '<field 
				name="more_info_btn_type_'.$lang_code.'" 
				type="list" 
				default="link" 
				showon="more_info_btn_'.$lang_code.':1" 
				label="J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_MORE_INFO_BTN_TYPE_LBL" 
				description="J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_MORE_INFO_BTN_TYPE_DESC">
				<option value="link">J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_MORE_INFO_BTN_TYPE_OPTION_LINK</option>
				<option value="menu_item">J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_MORE_INFO_BTN_TYPE_OPTION_MENU_ITEM</option>
				<option value="custom_text">J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_MORE_INFO_BTN_TYPE_OPTION_CUSTOM_TEXT</option>
				</field>';

				// CUSTOM TEXT
				$xmlText .= '<field 
				name="custom_text_'.$lang_code.'" 
				type="editor" 
				default="'.JText::_('J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_CUSTOM_TEXT_DEFAULT').'" 
				label="J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_CUSTOM_TEXT_LBL" 
				description="J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_CUSTOM_TEXT_DESC" 
				width="300" 
				filter="safehtml"
				showon="more_info_btn_'.$lang_code.':1[AND]more_info_btn_type_'.$lang_code.':custom_text" 
				/>';

				// CUSTOM LINK FOR THE MORE INFO BUTTON
				$xmlText .= '<field 
				name="button_more_link_'.$lang_code.'" 
				type="url" 
				default="cookies-policy" 
				label="J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_BUTTON_MORELINK_LBL" 
				description="J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_BUTTON_MORELINK_DESC" 
				showon="more_info_btn_'.$lang_code.':1[AND]more_info_btn_type_'.$lang_code.':link" 
				/>';
				
				// MODAL MENU ITEM
				$xmlText .= '<field 
				name="cpnb_modal_menu_item_'.$lang_code.'" 
				type="modal_menu"
				label="J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_SELECT_MENU_ITEM_LBL"
				description="J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_SELECT_MENU_ITEM_DESC"
				required="false"
				select="true"
				new="true"
				edit="true"
				clear="true"
				addfieldpath="/administrator/components/com_menus/models/fields" 
				showon="more_info_btn_'.$lang_code.':1[AND]more_info_btn_type_'.$lang_code.':menu_item" 
				/>';

				// LINK TARGET
				$xmlText .= '<field 
				name="link_target_'.$lang_code.'" 
				type="list" 
				default="_self" 
				showon="more_info_btn_'.$lang_code.':1[AND]more_info_btn_type_'.$lang_code.'!:custom_text" 
				label="J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_LINK_TARGET_LBL" 
				description="J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_LINK_TARGET_DESC">
				<option value="_self">J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_LINK_TARGET_SAME_LBL</option>
				<option value="_blank">J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_LINK_TARGET_NEW_LBL</option>
				<option value="popup">J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_LINK_TARGET_POPUP_WINDOW_LBL</option>
				</field>';

				// POPUP WINDOW WIDTH
				$xmlText .= '<field 
				name="popup_width_'.$lang_code.'" 
				type="text" 
				default="800" 
				showon="more_info_btn_'.$lang_code.':1[AND]link_target_'.$lang_code.':popup[AND]more_info_btn_type_'.$lang_code.'!:custom_text" 
				label="J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_POPUP_WINDOW_WIDTH_LBL" 
				description="J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_POPUP_WINDOW_WIDTH_DESC" 
				/>';

				// POPUP WINDOW HEIGHT
				$xmlText .= '<field 
				name="popup_height_'.$lang_code.'" 
				type="text" 
				default="600" 
				showon="more_info_btn_'.$lang_code.':1[AND]link_target_'.$lang_code.':popup[AND]more_info_btn_type_'.$lang_code.'!:custom_text" 
				label="J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_POPUP_WINDOW_HEIGHT_LBL" 
				description="J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_POPUP_WINDOW_HEIGHT_DESC" 
				/>';

				// OLD TEXTS FOR LANGUAGES
				$xmlText .= '<field 
				name="textsforlanguagesold" 
				id="textsforlanguagesold"
				type="textsforlanguagesold" 
				default="600" 
				addfieldpath="/plugins/system/cookiespolicynotificationbar/elements"
				/>';

				$xmlText .= '<field type="spacer" name="myspacer" hr="true" />';

				// closing xml file
				$xmlText .= '
						</fieldset>
					</fields>
				</form>';
				$xmlObj = new SimpleXMLElement($xmlText);
				$form->setField($xmlObj, 'params', true, 'texts_for_languages');
			}
		}

		public function getUsersFormFieldLoginAsUser($form, $usergroup_id, $usergroup_name)
		{
			if (isset($form))
			{
				// start building xml file
				$xmlText = '<?xml version="1.0" encoding="utf-8"?>
				<form>
					<fields>
						<fieldset>';

				// HEADER
				$xmlText .= '<field type="header" name="header_'.$usergroup_id.'" class="w357_small_header" label="'.$usergroup_name.' ('.JText::_('PLG_LOGINASUSER_USER_GROUP').')" />';
				
				// ENABLE/DISABLED FOR THIS USER GROUP
				$xmlText .= '<field 
				name="enable_'.$usergroup_id.'" 
				type="radio" 
				class="btn-group btn-group-yesno" 
				default="1" 
				label="PLG_LOGINASUSER_ENABLE_FOR_THIS_USERGROUP_LBL" 
				description="PLG_LOGINASUSER_ENABLE_FOR_THIS_USERGROUP_DESC">
				<option value="1">JENABLED</option>
				<option value="0">JDISABLED</option>
				</field>';

				// NOTE
				$xmlText .= '<field name="note_'.$usergroup_id.'" type="note" label="" description="'.JText::_('PLG_LOGINASUSER_USER_GROUP_NOTE').'" showon="enable_'.$usergroup_id.':1" />';

				// USERS
				$xmlText .= '<field 
				name="users_'.$usergroup_id.'" 
				type="sql" 
				label="PLG_LOGINASUSER_SELECT_ADMINS_LBL" 
				description="PLG_LOGINASUSER_SELECT_ADMINS_DESC" 
				query="SELECT u.id AS value, CONCAT(u.name,\' (\', ug.title, \')\') AS users_'.$usergroup_id.' FROM #__users AS u LEFT JOIN #__user_usergroup_map AS ugm ON u.id = ugm.user_id LEFT JOIN #__usergroups AS ug ON ugm.group_id = ug.id GROUP BY u.id ORDER BY u.name ASC" 
				multiple="true" 
				showon="enable_'.$usergroup_id.':1"
				/>';

				$xmlText .= '<field type="spacer" name="myspacer_'.$usergroup_id.'" hr="true" />';

				// closing xml file
				$xmlText .= '
						</fieldset>
					</fields>
				</form>';
				$xmlObj = new SimpleXMLElement($xmlText);
				$form->setField($xmlObj, '', true, 'permissions_for_loginasuser');
			}
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
	
		public function getLanguageImage($lang_code)
		{
			$db = JFactory::getDBO();
			$query = "SELECT image "
			."FROM #__languages "
			."WHERE lang_code = '".$lang_code."' AND published = 1"
			;
			$db->setQuery($query);
			$db->query();
			$result = $db->loadResult();
			
			// If there are more than one language
			if (count($result)):
				return $result;
			// If there is only one language
			else:
				return '';
			endif;
	
		}

	}
endif;