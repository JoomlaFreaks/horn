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

class JFormFieldRmvcookies extends JFormField {
	
	protected $type = 'Rmvcookies';

	protected function getLabel()
	{
		return JText::_('J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_CLEAN_COOKIES_LBL');	
	}

	protected function getInput()
	{	
		// useful vars
		JHTML::_('behavior.tooltip');
		$html = '';
		$current_url = JURI::getInstance()->toString();
		$current_url_redirect = str_replace('&delete_cookiesDirective=1', '', $current_url);
		$front_page_url = str_replace('administrator/', '', JURI::base());
		$delete_cookiesDirective = JRequest::getVar('delete_cookiesDirective', '0'); // 1: enabled, 0: disabled

		// get plugin params
		$db = JFactory::getDBO();	
		$db->setQuery("SELECT params FROM #__extensions WHERE element = 'cookiespolicynotificationbar' AND folder = 'system'");
		$plugin = $db->loadObject();
		$params = new JRegistry();
		$params->loadString($plugin->params);
		$cookie_name = $params->get('cookie_name', 'cookiesDirective');

		// display the debugging button
		if (!isset($_COOKIE[$cookie_name]) || !$delete_cookiesDirective):
			$html .= '<p>';
			$html .= '<span class="editlinktip hasTip" title="'.JText::_('J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_CLEAN_COOKIES_TOOLTIP').'" >';
			$html .= '<a href="'.$current_url.'&delete_cookiesDirective=1" class="btn btn-refresh"><i class="icon-star"></i> '.JText::_('J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_CLEAN_COOKIES_BTN').'</a> ';
			$html .= '</span>';
			$html .= '</p>';
		endif;

		// remove the cookie from browser
		if(isset($_COOKIE[$cookie_name]) && $delete_cookiesDirective):
			unset($_COOKIE[$cookie_name]);
		    setcookie($cookie_name, null, -1, '/');
		endif;
	
		// cookie deleted successfully
		if ($delete_cookiesDirective):
			$message = '<p><strong>'.JText::_('J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_COOKIE_DELETED').'</strong><br /><em>'.JText::_('J357_PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR_REFRESH').' <a href="'.$front_page_url.'" target="_blank">'.$front_page_url.'.</a></em></p>';
			$msg_type = 'notice';
			JFactory::getApplication()->redirect($current_url_redirect, $message, $msg_type, true );
		endif;
				
		return $html;		
	}
}