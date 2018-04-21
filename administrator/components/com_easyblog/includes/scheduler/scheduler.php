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

class EasyBlogScheduler extends EasyBlog
{
	/**
	 * Processes scheduled blog posts to be unpublished
	 *
	 * @since	4.0
	 * @access	public
	 */
	public function unpublish()
	{
		$model = EB::model('Blogs');

		$items = $model->getUnpublishScheduledPosts();

		if ($items) {

			foreach ($items as $item) {
				$post = EB::post($item->id, $item->created_by);			

				$post->unpublish();
			}

			// TODO: Turn this into language string.
			return EB::exception('Executed query to process posts to be unpublished.', EASYBLOG_MSG_SUCCESS);
		}
	}

	/**
	 * Processes scheduled blog posts to be published
	 *
	 * @since	4.0
	 * @access	public
	 */
	public function publish()
	{
		// Retrieve a list of scheduled posts
		$model = EB::model('Blogs');
		$items = $model->getScheduledPosts();

		if (!$items) {
			return EB::exception('No scheduled posts to process currently.', EASYBLOG_MSG_INFO);
		}

		foreach ($items as $item) {
			$post = EB::post($item->id, $item->created_by);

			// We know that all scheduled posts regardless if the author has access to publish or not should be published.
			// Since pending posts that are being scheduled needs to be approved by the admin first.
			$options = array('checkAcl' => false);

			try {
				$post->publish($options, true);
			} catch(Exception $e) {

			}

			// Since scheduled post is not consider as new post, we need to add a notification here.
			// To commented this is because post lib already process 1 time notify.
			// $post->notify();
		}
	}

	/**
	 * Processes the garbage collector
	 *
	 * @since	4.0
	 * @access	public
	 */
	public function removeTmpFiles()
	{
		$db = EB::db();
		$now = EB::date()->toMySQL();

		// Define how long the file will be in database before it gets deleted.
		$minutes = 120;

		$query = 'SELECT * FROM ' . $db->quoteName('#__easyblog_uploader_tmp') . ' '
				. 'WHERE ' . $db->quoteName('created') . ' <= DATE_SUB(' . $db->Quote($now). ' , INTERVAL ' . $minutes . ' MINUTE)';

		$db->setQuery($query);
		$file = $db->loadObject();

		if ($file) {
			$query	= 'DELETE FROM ' . $db->nameQuote('#__easyblog_uploader_tmp') . ' '
					. 'WHERE ' . $db->nameQuote('id') . ' = ' . $file->id;

			$db->setQuery($query);
			$db->query();

			JFile::delete(JPATH_ROOT . '/' . $file->path);

			return EB::exception('Temp files removed', EASYBLOG_MSG_INFO);
		}
	}

	/**
	 * Processes the garbage collector on blank posts that already passed 3 days
	 *
	 * @since	5.1
	 * @access	public
	 */
	public function removeBlankPosts()
	{
		$db = EB::db();
		$now = EB::date()->toMySQL();

		// Define how long the file will be in database before it gets deleted.
		$days = 3;

		$queryId = 'SELECT a.`id`';
		$queryDelete = 'DELETE a, b';

		$query = ' FROM ' . $db->qn('#__easyblog_post') . ' as a';
		$query .= '	inner join ' . $db->qn('#__easyblog_revisions') . ' as b on a.' . $db->qn('id') . ' = b.' . $db->qn('post_id');
		$query .= ' where a.' . $db->qn('published') . ' = ' . $db->Quote(EASYBLOG_POST_BLANK);
		$query .= ' and a.' . $db->qn('created') . ' <= DATE_SUB(' . $db->Quote($now) . ', INTERVAL ' . $days . ' DAY)';

		$queryId = $queryId . $query;
		$queryDelete = $queryDelete . $query;

		// Get lists of id first
		$db->setQuery($queryId);
		$rows = $db->loadObjectList();

		// Relocate all media files from blank post to user folder
		if ($rows) {
			foreach ($rows as $row) {
				$post = EB::post($row->id);
				
				// Relocate media file
				$post->relocateMediaFiles();
			}
		}

		$db->setQuery($queryDelete);
		$db->query();

		return EB::exception('Blank posts removed', EASYBLOG_MSG_INFO);
	}
}