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

// Load up our library
$modules = EB::modules($module, false, true);

$view = $modules->input->get('view', '', 'var');

// Return if this is not entry view
if ($view !== 'entry') {
	return;
}

$id = $modules->input->get('id', 0, 'default');
$post = EB::post($id);
$categories = $post->getCategories();
$blogger = EB::user($post->created_by);

require(JModuleHelper::getLayoutPath('mod_easyblogpostmeta', $params->get('layout', 'default')));

