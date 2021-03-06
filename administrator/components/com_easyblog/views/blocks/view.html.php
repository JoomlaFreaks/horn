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

require_once(JPATH_ADMINISTRATOR . '/components/com_easyblog/views.php');

class EasyBlogViewBlocks extends EasyBlogAdminView
{
	/**
	 * Displays the blocks listings
	 *
	 * @since	4.0
	 * @access	public
	 */
	public function display($tpl = null)
	{
		// Check for access
		$this->checkAccess('easyblog.manage.blocks');

		$layout = $this->getLayout();

		if (method_exists($this, $layout)) {
			return $this->$layout();
		}

		$model = EB::model('Blocks');


		$search = $model->getState('search', '');
		$filterGroup = $model->getState('filter_group', '');
		$filterState = $model->getState('filter_state', 'all');

		$options = array('filter_state' => $filterState, 'search' => $search, 'filter_group' => $filterGroup);

		// Set the heading
		$this->setHeading('COM_EASYBLOG_TITLE_BLOCKS', '', 'fa-cubes');

		
		$blocks = $model->getBlocks($options);
		$groups = $model->getGroups();
		$pagination = $model->getPagination($options);

		$limit = $model->getState('limit');

		$this->set('limit', $limit);
		$this->set('filterState', $filterState);
		$this->set('filterGroup', $filterGroup);
		$this->set('groups', $groups);
		$this->set('pagination', $pagination);
		$this->set('blocks', $blocks);
		$this->set('search', $search);

		parent::display('blocks/default');
	}

	/**
	 * Display block installation layout
	 *
	 * @since	5.1
	 * @access	public
	 */
	public function install($tpl = null)
	{
		$this->checkAccess('easyblog.manage.blocks');

		$this->setHeading('COM_EASYBLOG_TITLE_BLOCKS_INSTALL');

		parent::display('blocks/install');
	}

	public function registerToolbar()
	{
		$layout	= JRequest::getVar('layout');

		if ($layout == 'install') {
			JToolBarHelper::custom('blocks.upload', 'upload', '', JText::_('COM_EASYBLOG_UPLOAD_AND_INSTALL_BUTTON'), false);
			return;
		}

		JToolbarHelper::publishList('blocks.publish');
		JToolbarHelper::unpublishList('blocks.unpublish');
	}
}
