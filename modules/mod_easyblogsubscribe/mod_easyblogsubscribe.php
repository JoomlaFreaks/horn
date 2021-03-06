<?php
/**
* @package		EasyBlog
* @copyright	Copyright (C) 2010 - 2017 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

jimport('joomla.filesystem.file');

$file = JPATH_ADMINISTRATOR . '/components/com_easyblog/includes/easyblog.php';

if (!JFile::exists($file)) {
	return;
}

require_once($file);

// Load up our library
$modules = EB::modules($module);

// Get necessary input
$option = $modules->input->get('option', '', 'cmd');
$view = $modules->input->get('view', '', 'cmd');
$id = $modules->input->get('id', 0, 'int');

// Get subscribe type
$type = $params->get('subscription_type', 'site');

// Allowed view
$allowed = array('categories', 'blogger', 'teamblog', 'entry');

// Site type subscription will appear everywhere.
if ($type != 'site') {

	if ($option != 'com_easyblog') {
		return;
	}

	if (!in_array($view, $allowed)) {
		return;
	}

	if ($view != $type) {
		return;
	}

	if (!$id) {
		return;
	}

	// update the type to the correct constant
	if ($type == 'categories') {
		$type = EBLOG_SUBSCRIPTION_CATEGORY;
	}

	if ($type == 'teamblog') {
		$type = EBLOG_SUBSCRIPTION_TEAMBLOG;
	}
} else {
	// if subscription type is site, the id must be 0
	$id = 0;
}



$formType = $params->get('type', 'link');

// We need to render the stylesheet regardless if it is form or dialog
// Because if there is an invalid email on the form version, the dialog is shown too.
$modules->renderSiteStylesheet();

// Determines if the current user is subscribed
$subscribed = false;
$my = JFactory::getuser();

// Get return url
$return = base64_encode(JRequest::getUri());

if (!$my->guest) {
	$subscription = EB::table('Subscriptions');
	$exists = $subscription->load(array('uid' => $id, 'utype' => $type, 'user_id' => $my->id));

	// If subscribed, we need to display unsubscribe button instead
	if ($exists) {
		$subscribed = $subscription->id;
	}
}

require(JModuleHelper::getLayoutPath('mod_easyblogsubscribe', $params->get('layout', 'default')));
