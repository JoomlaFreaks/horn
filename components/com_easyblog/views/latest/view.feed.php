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

require_once(JPATH_COMPONENT . '/views/views.php');

class EasyBlogViewLatest extends EasyBlogView
{
	public function display($tpl = null)
	{
		// Ensure that rss is enabled
		if (!$this->config->get('main_rss')) {
			return;
		}

		// Set the document properties
		$this->doc->link = EB::_('index.php?option=com_easyblog&view=latest');
		$this->doc->setTitle(JText::_('COM_EASYBLOG_FEEDS_LATEST_TITLE'));
		$this->doc->setDescription(JText::sprintf('COM_EASYBLOG_FEEDS_LATEST_DESC', JURI::root()));

		$posts = $this->getPosts();

		if (!$posts) {
			return;
		}

		$this->doc->items = EB::formatter('feeds', $posts);

		return;
	}

	/**
	 * Retrieves frontpage posts
	 *
	 * @since	5.1
	 * @access	public
	 */
	public function getPosts()
	{
		// Get sorting options
		$sort = $this->input->get('sort', $this->config->get('layout_postorder'), 'cmd');

		// Get the current active menu's properties.
		$params = $this->theme->params;
		$inclusion	= '';

		if ($params) {

			// Get a list of category inclusions
			$inclusion = EB::getCategoryInclusion($params->get('inclusion'));

			if ($params->get('includesubcategories', 0) && !empty($inclusion)) {

				$tmpInclusion = array();

				foreach ($inclusion as $includeCatId) {

					// Retrieve nested categories
					$category = new stdClass();
					$category->id = $includeCatId;
					$category->childs = null;

					EB::buildNestedCategories($category->id, $category);

					$linkage = '';
					EB::accessNestedCategories($category, $linkage, '0', '', 'link', ', ');

					$catIds = array();
					$catIds[] = $category->id;
					EB::accessNestedCategoriesId($category, $catIds);

					$tmpInclusion = array_merge($tmpInclusion, $catIds);
				}

				$inclusion = $tmpInclusion;
			}
		}

		// Get the blogs model
		$model = EB::model('Blog');

		// Retrieve a list of featured blog posts on the site.
		$featured = $model->getFeaturedBlog();
		$excludeIds = array();

		// Test if user also wants the featured items to be appearing in the blog listings on the front page.
		// Otherwise, we'll need to exclude the featured id's from appearing on the front page.
		if (!$this->theme->params->get('post_include_featured', true)) {
			foreach ($featured as $item) {
				$excludeIds[] = $item->id;
			}
		}

		// Try to retrieve any categories to be excluded.
		$excludedCategories = array();
		if ($params->get('exclusion_categories', false)) {
			$excludedCategories = $params->get('exclusion_categories');

		} else {
			// upgrades compatibility
			$tmpExcludeCategories = $this->config->get('layout_exclude_categories', null);
			if ($tmpExcludeCategories) {
				$excludedCategories	= explode( ',' , $tmpExcludeCategories );
			}
		}

		$posts = $model->getBlogsBy('', '', $sort, 0, EBLOG_FILTER_PUBLISHED, null, true, $excludeIds, false, false, true, $excludedCategories, $inclusion, null, 'listlength', $this->theme->params->get('post_pin_featured', false));

		return $posts;
	}
}
