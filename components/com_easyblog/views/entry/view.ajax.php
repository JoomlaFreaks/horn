<?php
/**
* @package		EasyBlog
* @copyright	Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

require_once(JPATH_COMPONENT . '/views/views.php');

class EasyBlogViewEntry extends EasyBlogView
{
	/**
	 * Displays confirmation to publish a previewed post
	 *
	 * @since	5.1
	 * @access	public
	 */
	public function confirmUseRevision()
	{
		$uid = $this->input->get('uid', '', 'default');

		$post = EB::post($uid);

		if (!$uid || !$post->id) {
			return JError::raiseError(500, JText::_('COM_EASYBLOG_INVALID_ID_PROVIDED'));
		}

		// Default return url.
		$return = base64_encode($post->getPermalink());

		// Theme uses back end language file
		EB::loadLanguages(JPATH_ADMINISTRATOR);
		
		$theme = EB::template();
		$theme->set('post', $post);
		$theme->set('return', $return);

		$output = $theme->output('site/blogs/entry/dialogs/userevision');

		return $this->ajax->resolve($output);
	}

	/**
	 * Displays confirmation to publish a previewed post
	 *
	 * @since	5.1
	 * @access	public
	 */
	public function confirmPublish()
	{
		$id = $this->input->get('id', 0, 'int');

		$post = EB::post($id);

		if (!$id || !$post->id) {
			return JError::raiseError(500, JText::_('COM_EASYBLOG_INVALID_ID_PROVIDED'));
		}

		// Default return url.
		$return = base64_encode($post->getPermalink());

		// Theme uses back end language file
		EB::loadLanguages(JPATH_ADMINISTRATOR);
		
		$theme = EB::template();
		$theme->set('post', $post);
		$theme->set('return', $return);

		$output = $theme->output('site/blogs/entry/dialogs/publish');

		return $this->ajax->resolve($output);
	}
	
	/**
	 * Move the post to trash 
	 *
	 * @since	5.1
	 * @access	public
	 */
	public function trash()
	{
		// Get the post id
		$id = $this->input->get('id', 0, 'int');

		// Load up the blog post
		$post = EB::post($id);

		if (!$id || !$post->id) {
			return JError::raiseError(500, JText::_('COM_EASYBLOG_INVALID_ID_PROVIDED'));
		}

		// Check if the user has access to approve
		if (!$post->canDelete()) {
			return $this->ajax->reject(500, JText::_('COM_EASYBLOG_NOT_ALLOWED'));
		}

		$post->trash();
		
		$this->info->set(JText::_('COM_EASYBLOG_DASHBOARD_TRASH_SUCCESS'), 'success');

		$return = EB::_('index.php?option=com_easyblog&view=latest', false);

		return $this->ajax->redirect($return);
	}

	/**
	 * Publish the blog post
	 *
	 * @since	5.1
	 * @access	public
	 */
	public function publish()
	{
		// Check for request forgeries
		EB::checkToken();

		// Get the post id
		$id = $this->input->get('id', 0, 'int');

		// Load up the blog post
		$post = EB::post($id);

		if (!$id || !$post->id) {
			return JError::raiseError(500, JText::_('COM_EASYBLOG_INVALID_ID_PROVIDED'));
		}

		$post->publish();
		
		$this->info->set(JText::_('COM_EASYBLOG_POSTS_PUBLISHED_SUCCESS'), 'success');

		$return = $post->getPermalink();

		return $this->ajax->redirect($return);
	}
	
	/**
	 * Submit the post for approval
	 *
	 * @since	5.1
	 * @access	public
	 */
	public function submitApproval()
	{
		// Check for request forgeries
		EB::checkToken();

		// Get the post id
		$id = $this->input->get('id', 0, 'int');

		// Load up the blog post
		$post = EB::post($id);

		if (!$id || !$post->id) {
			return JError::raiseError(500, JText::_('COM_EASYBLOG_INVALID_ID_PROVIDED'));
		}

		$post->published = EASYBLOG_POST_PENDING;

		try {
			$post->save();
		} catch (Exception $e) {
			$this->info->set($e->getMessage(), 'error');

			$return = $post->getPreviewLink(false);

			return $this->ajax->redirect($return);
		}

		$this->info->set(JText::_('COM_EASYBLOG_POST_SUBMITTED_FOR_APPROVAL'), 'success');

		$return = EBR::_('index.php?option=com_easyblog&view=dashboard&layout=entries', false);

		return $this->ajax->redirect($return);
	}

	/**
	 * Displays confirmation to unarchive a post
	 *
	 * @since	5.1
	 * @access	public
	 */
	public function confirmUnarchive()
	{
		// Get the blog post id
		$id = $this->input->get('id', 0, 'int');

		// Load up the blog post
		$post = EB::post($id);

		if (!$id || !$post->id) {
			return JError::raiseError(500, JText::_('COM_EASYBLOG_INVALID_ID_PROVIDED'));
		}

		// Check if the user has access to approve
		if (!$post->canModerate()) {
			return $this->ajax->reject(500, JText::_('COM_EASYBLOG_NO_PERMISSIONS_TO_MODERATE'));
		}

		// Get the return url if there's any so that we can redirect them accordingly later
		$return = $this->input->get('return', '', 'default');

		$theme = EB::template();
		$theme->set('post', $post);
		$theme->set('return', $return);
		$output = $theme->output('site/blogs/entry/dialogs/unarchive');

		return $this->ajax->resolve($output);
	}

	/**
	 * Displays confirmation to archive a post
	 *
	 * @since	5.1
	 * @access	public
	 */
	public function confirmArchive()
	{
		// Get the blog post id
		$id = $this->input->get('id', 0, 'int');

		// Load up the blog post
		$post = EB::post($id);

		if (!$id || !$post->id) {
			return JError::raiseError(500, JText::_('COM_EASYBLOG_INVALID_ID_PROVIDED'));
		}

		// Check if the user has access to approve
		if (!$post->canModerate()) {
			return $this->ajax->reject(500, JText::_('COM_EASYBLOG_NO_PERMISSIONS_TO_MODERATE'));
		}

		// Get the return url if there's any so that we can redirect them accordingly later
		$return = $this->input->get('return', '', 'default');

		$theme = EB::template();
		$theme->set('post', $post);
		$theme->set('return', $return);
		$output = $theme->output('site/blogs/entry/dialogs/archive');

		return $this->ajax->resolve($output);
	}

	/**
	 * Displays a trash confirmation dialog
	 *
	 * @since	5.1
	 * @access	public
	 */
	public function confirmDelete()
	{
		// Get the blog post id
		$id = $this->input->get('id', 0, 'int');

		// Load up the blog post
		$post = EB::post($id);

		if (!$id || !$post->id) {
			return JError::raiseError(500, JText::_('COM_EASYBLOG_INVALID_ID_PROVIDED'));
		}

		// Check if the user has access to delete.
		if (!$post->canDelete()) {
			return $this->ajax->reject(500, JText::_('COM_EASYBLOG_NO_PERMISSIONS_TO_MODERATE'));
		}

		// Get the return url if there's any so that we can redirect them accordingly later
		$return = $this->input->get('return', '', 'default');

		$theme = EB::template();
		$theme->set('post', $post);
		$theme->set('return', $return);
		$output = $theme->output('site/blogs/entry/dialogs/trash');

		return $this->ajax->resolve($output);
	}

	/**
	 * Renders the dialog confirmation to unpublish a post
	 *
	 * @since	5.1
	 * @access	public
	 */
	public function confirmUnpublish()
	{
		$ajax = EB::ajax();

		// Get the blog post id
		$id = $this->input->get('id', 0, 'int');

		// Load up the blog post
		$post = EB::post($id);

		if (!$id || !$post->id) {
			return JError::raiseError(500, JText::_('COM_EASYBLOG_INVALID_ID_PROVIDED'));
		}

		// Check if the user has access to approve
		if (!$post->canModerate() && !$post->canPublish()) {
			return $this->ajax->reject(500, JText::_('COM_EASYBLOG_NO_PERMISSIONS_TO_MODERATE'));
		}

		// Get the return url if there's any so that we can redirect them accordingly later
		$return = $this->input->get('return', '', 'default');

		$theme = EB::template();
		$theme->set('post', $post);
		$theme->set('return', $return);
		$output = $theme->output('site/blogs/entry/dialogs/unpublish');

		return $ajax->resolve($output);
	}

}

