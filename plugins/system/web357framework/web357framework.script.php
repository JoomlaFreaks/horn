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

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// http://docs.joomla.org/J2.5:Developing_a_MVC_Component/Adding_an_install-uninstall-update_script_file
if (!class_exists("plgSystemWeb357FrameworkInstallerScript")):
class plgSystemWeb357FrameworkInstallerScript
{
	var $extension_type = '';
	var $extension_name = '';
	var $extension_real_name = '';
	
	function __construct()
	{
		$this->extension_type = "plugin";
		$this->extension_name = "cookiespolicynotificationbar";
		$this->extension_real_name = "Cookies Policy Notification Bar";
	}

	/**
	 * method to install the component
	 *
	 * @return void
	 */
	function install($parent) 
	{
		// $parent is the class calling this method
	}
 
	/**
	 * method to uninstall the component
	 *
	 * @return void
	 */
	function uninstall($parent) 
	{
		// $parent is the class calling this method
	}
 
	/**
	 * method to update the component
	 *
	 * @return void
	 */
	function update($parent) 
	{
		// $parent is the class calling this method
	}
 
	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @return void
	 */
	function preflight($type, $parent) 
	{
		// $parent is the class calling this method
		// $type is the type of change (install, update or discover_install)
		JFactory::getLanguage()->load('plg_system_web357framework', __DIR__);
	}
 
	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	function postflight($type, $parent) 
	{
		if (!in_array($type, array('install', 'update')))
		{
			return;
		}
		
		// Connect to DB
		$db = JFactory::getDbo();
		
		// Delete old names of Extensions
		$query = "DELETE FROM #__extensions WHERE element='com_web357loginasuser'"; $db->setQuery($query); $db->query();
		$query = "DELETE FROM #__extensions WHERE element='com_web357fix404errorlinks'"; $db->setQuery($query); $db->query();
		$query = "DELETE FROM #__extensions WHERE element='com_virtuemartsales'"; $db->setQuery($query); $db->query();
		$query = "DELETE FROM #__extensions WHERE name='MOD_TOOLBAR' AND element='mod_toolbar' AND client_id=0 AND protected=0"; $db->setQuery($query); $db->query();
		
		// BEGIN: Delete Old packages
		// components
		$query = "DELETE FROM #__extensions WHERE element='pkg_vmsales'"; $db->setQuery($query); $db->query();
		$query = "DELETE FROM #__extensions WHERE element='pkg_fix404errorlinks'"; $db->setQuery($query); $db->query();
		$query = "DELETE FROM #__extensions WHERE element='pkg_loginasuser'"; $db->setQuery($query); $db->query();
		
		// modules
		$query = "DELETE FROM #__extensions WHERE element='pkg_toolbar'"; $db->setQuery($query); $db->query();
		$query = "DELETE FROM #__extensions WHERE element='pkg_fixedhtmltoolbar'"; $db->setQuery($query); $db->query();
		$query = "DELETE FROM #__extensions WHERE element='pkg_vmcountproducts'"; $db->setQuery($query); $db->query();
		$query = "DELETE FROM #__extensions WHERE element='pkg_datetime'"; $db->setQuery($query); $db->query();
		$query = "DELETE FROM #__extensions WHERE element='pkg_countdown'"; $db->setQuery($query); $db->query();

		// plugins
		$query = "DELETE FROM #__extensions WHERE element='pkg_failedloginattempts'"; $db->setQuery($query); $db->query();
		$query = "DELETE FROM #__extensions WHERE element='pkg_cookiespolicynotificationbar'"; $db->setQuery($query); $db->query();
		// END: Delete Old packages
	
		// BEGIN: Update and Set the correct names for extensions.
		// components
		$query = "UPDATE #__extensions SET name='COM_VMSALES' WHERE element='com_vmsales'"; $db->setQuery($query); $db->query();
		$query = "UPDATE #__extensions SET name='COM_FIX404ERRORLINKS' WHERE element='com_fix404errorlinks'"; $db->setQuery($query); $db->query();
		$query = "UPDATE #__extensions SET name='COM_LOGINASUSER' WHERE element='com_loginasuser'"; $db->setQuery($query); $db->query();
		
		// modules
		$query = "UPDATE #__extensions SET name='MOD_FIXEDHTMLTOOLBAR' WHERE element='mod_fixedhtmltoolbar'"; $db->setQuery($query); $db->query();
		$query = "UPDATE #__extensions SET name='MOD_VMCOUNTPRODUCTS' WHERE element='mod_vmcountproducts'"; $db->setQuery($query); $db->query();
		$query = "UPDATE #__extensions SET name='MOD_DATETIME' WHERE element='mod_datetime'"; $db->setQuery($query); $db->query();
		$query = "UPDATE #__extensions SET name='MOD_COUNTDOWN' WHERE element='mod_countdown'"; $db->setQuery($query); $db->query();
		
		// plugins
		$query = "UPDATE #__extensions SET name='PLG_AUTHENTICATION_FAILEDLOGINATTEMPTS' WHERE element='failedloginattempts'"; $db->setQuery($query); $db->query();
		$query = "UPDATE #__extensions SET name='PLG_SYSTEM_COOKIESPOLICYNOTIFICATIONBAR' WHERE element='cookiespolicynotificationbar'"; $db->setQuery($query); $db->query();
		$query = "UPDATE #__extensions SET name='PLG_SYSTEM_WEB357FRAMEWORK' WHERE element='web357framework'"; $db->setQuery($query); $db->query();
		$query = "UPDATE #__extensions SET name='PLG_SYSTEM_VMSALES' WHERE element='vmsales'"; $db->setQuery($query); $db->query();
		$query = "UPDATE #__extensions SET name='PLG_SYSTEM_FIX404ERRORLINKS' WHERE element='fix404errorlinks'"; $db->setQuery($query); $db->query();
		$query = "UPDATE #__extensions SET name='PLG_SYSTEM_LOGINASUSER' WHERE element='loginasuser'"; $db->setQuery($query); $db->query();
		// END: Update Extensions

		// Update Menu
		$query = "UPDATE #__menu SET title='COM_VMSALES', alias='com-vmsales', path='com-vmsales' WHERE link='index.php?option=com_vmsales'"; $db->setQuery($query); $db->query();
		$query = "UPDATE #__menu SET title='COM_FIX404ERRORLINKS', alias='com-fix404errorlinks', path='com-fix404errorlinks' WHERE link='index.php?option=com_fix404errorlinks'"; $db->setQuery($query); $db->query();
		$query = "UPDATE #__menu SET title='COM_LOGINASUSER', alias='com-loginasuser', path='com-loginasuser' WHERE link='index.php?option=com_loginasuser'"; $db->setQuery($query); $db->query();

		// Define DS
        if (!defined("DS")):
			define("DS", DIRECTORY_SEPARATOR);
		endif;

        // Import filesystem libraries. Perhaps not necessary, but does not hurt
        jimport('joomla.filesystem.file');

        // Remove files from older versions
		$remove_files_arr = array(
			// supporthours
			JPATH_ROOT.DS.'modules'.DS.'mod_supporthours'.DS.'timezone.php', 
			
			// failedloginattempts
			JPATH_ROOT.DS.'plugins'.DS.'authentication'.DS.'failedloginattempts'.DS.'elements'.DS.'info.php',
			JPATH_ROOT.DS.'plugins'.DS.'authentication'.DS.'failedloginattempts'.DS.'elements'.DS.'footer.php',

			// cookiespolicynotificationbar
			JPATH_ROOT.DS.'plugins'.DS.'system'.DS.'cookiespolicynotificationbar'.DS.'elements'.DS.'info.php',
		);
			
        // Remove action
		foreach ($remove_files_arr as $remove_file):
			if (JFile::exists($remove_file)):
                JFile::delete($remove_file);
			endif;
		endforeach;

        // Import the folder system library
		jimport('joomla.filesystem.folder');

        // Remove folders from older versions
        $remove_folders_arr = array();
        
        // Remove action
		if (!empty($remove_folders_arr)):
			foreach ($remove_folders_arr as $remove_folder):
				if (JFolder::exists($remove_folder)):
					JFolder::delete($remove_folder);
				endif;
			endforeach;
		endif;

		// Connect to DB
		$db = JFactory::getDbo();
		
		// BEGIN: Enable Plugin
		$query = "UPDATE #__extensions SET enabled=1 WHERE element='web357framework' AND type='plugin'";
		$db->setQuery($query);
		$db->query();
		// END: Enable Plugin

		// display product's description
		require_once(JPATH_PLUGINS."/system/web357framework/elements/description.php");
		$class = new JFormFieldDescription;
		echo $class->getHtmlDescription($this->extension_type, $this->extension_name, "", $this->extension_real_name);
		echo "<hr><br><br>";

		// Install the rest of the packages
		$this->installPackages();
	}

	private function installPackages()
	{
		$packages = JFolder::folders(__DIR__ . '/packages');

		$packages = array_diff($packages, array('plg_system_web357framework'));

		foreach ($packages as $package):
			if (!$this->installPackage($package)):
				return false;
			endif;
		endforeach;

		return true;
	}

	private function installPackage($package)
	{
		$tmpInstaller = new JInstaller;

		return $tmpInstaller->install(__DIR__ . '/packages/' . $package);
	}

}
endif;