<?php
/**
* @package      EasyBlog
* @copyright    Copyright (C) 2010 - 2017 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
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

// Render site stylesheet
$modules->renderSiteStylesheet();

// @5.1
// Backward compatibility
$config = $modules->config;

$count = (int) trim($params->get('count', 0));
$model = EB::model('Blog');

$type = '';
$cid = $params->get('catid', '');

if (!empty($cid)) {
	$type = 'category';

	// Normalize the categories to be an array
	if (!is_array($cid)) {
		$cid = array($cid);
	}
}

$disabled = $params->get('enableratings') ? false : true;
$posts = $model->getBlogsBy($type, $cid, 'popular', $count, EBLOG_FILTER_PUBLISHED, null, false);

$posts = $modules->processItems($posts);
$textcount = $params->get('textcount', 150);
$layout = $params->get('module_layout', 'vertical');

// EasyBlog 5.0.x backward compatible fixes
if (!in_array($layout, array('vertical', 'horizontal'))) {
	$layout = 'vertical';
}

$columnCount = $params->get('column');

// Get the photo layout option
$photoLayout = $modules->getCoverLayout();
$photoLayout = $params->get('photo_layout');
$photoSize = $params->get('photo_size', 'medium');

$photoAlignment = $params->get('alignment', 'center');
$photoAlignment = ($photoAlignment == 'default') ? 'center' : $photoAlignment;

require(JModuleHelper::getLayoutPath('mod_easyblogmostpopularpost', $params->get('layout', 'default')));
