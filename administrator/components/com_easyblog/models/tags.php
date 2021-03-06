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

require_once(__DIR__ . '/model.php');

class EasyBlogModelTags extends EasyBlogAdminModel
{
	public $total = null;

	public function __construct()
	{
		parent::__construct();

		$limit = $this->app->getUserStateFromRequest('com_easyblog.tags.limit', 'limit', $this->app->getCfg('list_limit'), 'int');
		$limitstart = $this->app->getUserStateFromRequest('com_easyblog.tags.limitstart', 'limitstart', 0, 'int');

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}

	/**
	 * Populate current stats
	 *
	 * @since	5.1
	 * @access	public
	 */
	protected function populateState()
	{
		parent::populateState();
	}

	/**
	 * Generates pagination for the back end
	 *
	 * @since	5.1
	 * @access	public
	 */
	public function getPagination()
	{
		jimport('joomla.html.pagination');

		$pagination = new JPagination($this->total, $this->getState('limitstart'), $this->getState('limit'));

		return $pagination;
	}

	/**
	 * Generates the listing of tags at the back end
	 *
	 * @since	5.1
	 * @access	public
	 */
	public function getData()
	{
		$db = EB::db();
		$query = array();
		$query[] = 'SELECT * FROM ' . $db->qn('#__easyblog_tag');
		$query[] = 'WHERE 1';

		// Filters
		$filter = $this->app->getUserStateFromRequest('com_easyblog.tags.filter_state', 'filter_state', '', 'word');

		if ($filter == 'P') {
			$query[] = 'AND ' . $db->qn('published') . '=' . $db->Quote('1');
		}

		if ($filter == 'U') {
			$query[] = 'AND ' . $db->qn('published') . '=' . $db->Quote('0');
		}

		// Search
		$search = $this->app->getUserStateFromRequest('com_easyblog.tags.search', 'search', '', 'string');
		$search = JString::trim(JString::strtolower($search));

		if ($search) {
			$query[] = 'AND LOWER(' . $db->qn('title') . ') LIKE ' . $db->Quote('%' . $search . '%');
		}

		// Ordering
		$ordering = $this->app->getUserStateFromRequest('com_easyblog.tags.filter_order', 'filter_order', 'ordering', 'cmd');
		$orderingDirection = $this->app->getUserStateFromRequest('com_easyblog.tags.filter_order_Dir', 'filter_order_Dir', '', 'word');

		$query[] = 'ORDER BY ' . $ordering . ' ' . $orderingDirection . ', ' . $db->qn('ordering');

		$query = implode(' ', $query);

		// First we get the total number of records before pagination
		$queryLimit = 'SELECT COUNT(1) ' . str_ireplace(array('SELECT *'), '', $query);
		$db->setQuery($queryLimit);

		$this->total = (int) $db->loadResult();

		$limitstart = $this->getState('limitstart');
		$limit = $this->getState('limit');

		// Reset the limitstart (perhaps caused by other filters)
		if ($this->total <= $limitstart) {
			$limitstart = 0;
			$this->setState('limitstart', 0);
		}

		if ($limit) {
			$query .= ' LIMIT ' . $limitstart . ',' . $limit;
		}

		$db->setQuery($query);
		$data = $db->loadObjectList();

		return $data;
	}

	/**
	 * Set the row's state given the column
	 *
	 * @since	1.3
	 * @access	private
	 * @param	string
	 * @return
	 */
	private function setColumn($ids, $column, $state = true)
	{
		if (!$ids) {
			return false;
		}

		$db = EB::db();
		$tags = '';

		foreach ($ids as $id) {
			$tags .= $id;

			if (next($ids) !== false) {
				$tags .= ',';
			}
		}

		$state = (int) $state;

		$query	= 'UPDATE ' . $db->quoteName('#__easyblog_tag');
		$query .= ' SET ' . $db->quoteName($column) . '=' . $db->Quote($state);

		$query .= ' WHERE ' . $db->quoteName('id') . ' IN (' . $tags . ')';

		$db->setQuery($query);

		return $db->Query();
	}

	/**
	 * Method to unpublish tags
	 *
	 * @since	4.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function unpublishItems($ids)
	{
		return $this->setColumn($ids, 'published', false);
	}

	/**
	 * Method to publish tags
	 *
	 * @since 4.0
	 * @access public
	 * @return array
	 */
	public function publishItems($ids)
	{
		return $this->setColumn($ids, 'published', true);
	}

	public function searchTag($title)
	{
		$db	= EB::db();

		$query	= 'SELECT ' . $db->nameQuote('id') . ' '
				. 'FROM ' 	. $db->nameQuote('#__easyblog_tag') . ' '
				. 'WHERE ' 	. $db->nameQuote('title') . ' = ' . $db->quote($title) . ' '
				. 'LIMIT 1';
		$db->setQuery($query);

		$result	= $db->loadObject();

		return $result;
	}


	/**
	 * Returns the number of blog entries created within this category.
	 *
	 * @return int	$result	The total count of entries.
	 * @param boolean	$published	Whether to filter by published.
	 */
	public function getUsedCount( $tagId , $published = false )
	{
		$db			= EB::db();

		$query  = 'SELECT COUNT(a.tag_id) FROM `#__easyblog_post_tag` as a';
		$query  .= ' INNER JOIN `#__easyblog_post` as b ON a.post_id = b.id';
		$query  .= ' WHERE `tag_id` = ' . $db->Quote( $tagId );

		if( $published )
		{
			$query	.= ' AND a.`published` = ' . $db->Quote( 1 );
		}

		$db->setQuery( $query );

		$result	= $db->loadResult();

		return $result;
	}

	/**
	 * Allows caller to set the state of the default
	 *
	 * @since	4.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function setDefault($ids)
	{
		return $this->setColumn($ids, 'default', true);
	}

	/**
	 * Retrieves tags created by an author author
	 *
	 * @since	4.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getBloggerTags($authorId = null, $sort = 'asc', $search = '', $limit = null)
	{
		$authorId = JFactory::getUser($authorId)->id;
		$db = EB::db();

		$query = array();

		$query[] = 'SELECT SQL_CALC_FOUND_ROWS a.*, COUNT(b.' . $db->quoteName('id') . ') AS ' . $db->quoteName('post_count');
		$query[] = 'FROM ' . $db->quoteName('#__easyblog_tag') . ' AS a';
		$query[] = 'LEFT JOIN ' . $db->quoteName('#__easyblog_post_tag') . ' AS b';
		$query[] = 'ON a.' . $db->quoteName('id') . ' = b.' . $db->quoteName('tag_id');
		$query[] = 'WHERE a.' . $db->quoteName('created_by') . '=' . $db->Quote($authorId);

		if ($search) {
			$query[] = 'AND a.' . $db->quoteName('title') . ' LIKE (' . $db->Quote('%' . $search . '%') . ')';
		}

		$query[] = 'GROUP BY a.' . $db->quoteName('id');


		if ($sort == 'post') {
			$query[] = 'ORDER BY COUNT(b.' . $db->quoteName('id') . ') DESC';
		}

		if ($sort == 'asc') {
			$query[] = 'ORDER BY a.' . $db->quoteName('title') . ' ASC';
		}

		if ($sort == 'desc') {
			$query[] = 'ORDER BY a.' . $db->quoteName('title') . ' DESC';
		}

		$limit	= ($limit == 0) ? $this->getState('limit') : $limit;

		if ($limit) {
			$this->setState('limit', $limit);
		}

		$limitstart = $this->input->get('limitstart', $this->getState('limitstart'), 'int');
		$limitSQL = 'LIMIT ' . $limitstart . ',' . $limit;

		$query[] = $limitSQL;

		$query = implode(' ', $query);

		$db->setQuery($query);

		$result = $db->loadObjectList();

		// Get total tags
		$cntQuery = 'select FOUND_ROWS()';
		$db->setQuery($cntQuery);

		$this->_total = $db->loadResult();

		return $result;
	}

	/**
	 * Allows caller to remove default
	 *
	 * @since	4.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function removeDefault($ids)
	{
		return $this->setColumn($ids, 'default', false);
	}


	/**
	 * Method to publish or unpublish tags
	 *
	 * @access public
	 * @return array
	 */
	public function publish(&$tags, $publish = 1)
	{
		if( count( $tags ) > 0 )
		{
			$db		= EB::db();

			$tags	= implode( ',' , $tags );

			$query	= 'UPDATE ' . $db->nameQuote( '#__easyblog_tag' ) . ' '
					. 'SET ' . $db->nameQuote( 'published' ) . '=' . $db->Quote( $publish ) . ' '
					. 'WHERE ' . $db->nameQuote( 'id' ) . ' IN (' . $tags . ')';
			$db->setQuery( $query );

			if( !$db->query() )
			{
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			return true;
		}
		return false;
	}

	/**
	 * Method to get total tags created so far iregardless the status.
	 *
	 * @access public
	 * @return integer
	 */
	public function getTotalTags( $userId = 0)
	{
		$db		= EB::db();
		$where  = array();

		$query	= 'SELECT COUNT(1) FROM ' . $db->nameQuote( '#__easyblog_tag' );

		if(! empty($userId))
			$where[]  = '`created_by` = ' . $db->Quote($userId);

		$extra 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
		$query      = $query . $extra;



		$db->setQuery( $query );

		$result	= $db->loadResult();

		return (empty($result)) ? 0 : $result;
	}

	/**
	 * Displays a list of tags created by the author
	 *
	 * @since	4.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function _buildQueryByBlogger($bloggerId, $sort = 'title')
	{
		$db			= EB::db();

		$query	= 	'select a.`id`, a.`title`, a.`alias`, a.`created`, count(b.`id`) as `post_count`, a.`published`';
		$query	.=  ' from #__easyblog_tag as a';
		$query	.=  '    left join #__easyblog_post_tag as b';
		$query	.=  '    on a.`id` = b.`tag_id`';
		$query	.=  ' where a.created_by = ' . $db->Quote($bloggerId);
		$query	.=  ' group by (a.`id`)';

		if( $sort == 'post')
			$query	.=  ' order by count(b.`id`) desc';
		else
			$query	.=  ' order by a.`title`';

		return $query;
	}

	/**
	 * Retrieves the list of tags created by an author
	 *
	 * @since	4.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getTagsByBlogger($bloggerId, $usePagination = true, $sort = 'title')
	{
		$db = EB::db();

		$query = $this->_buildQueryByBlogger($bloggerId, $sort);

		$result = null;
		if( $usePagination )
		{
			$pg		= $this->getPaginationByBlogger($bloggerId, $sort);
			$result = $this->_getList($query, $pg->limitstart, $pg->limit);
		}
		else
		{
			$result = $this->_getList( $query );
		}

		return $result;
	}

	public function getPaginationByBlogger($bloggerId, $sort = 'title')
	{
		jimport('joomla.html.pagination');
		$this->_pagination	= EB::pagination( $this->getTotalByBlogger( $bloggerId , $sort ), $this->getState('limitstart'), $this->getState('limit') );
		return $this->_pagination;
	}

	public function getTotalByBlogger($bloggerId, $sort = 'title')
	{
		// Lets load the content if it doesn't already exist
		$query = $this->_buildQueryByBlogger($bloggerId, $sort);
		$total = $this->_getListCount($query);

		return $total;
	}

	/**
	 * *********************************************************************
	 * END: These part of codes will used in dashboard tags.
	 * *********************************************************************
	 */

	public function isExist($tagName, $excludeTagIds='0')
	{
		$db = EB::db();

		$query  = 'SELECT COUNT(1) FROM #__easyblog_tag';
		$query  .= ' WHERE `title` = ' . $db->Quote($tagName);
		if($excludeTagIds != '0')
			$query  .= ' AND `id` != ' . $db->Quote($excludeTagIds);

		$db->setQuery($query);
		$result = $db->loadResult();

		return (empty($result)) ? 0 : $result;
	}

	/**
	 * Generates a list of tags on the site
	 *
	 * @since	5.1.5
	 * @access	public
	 */
	public function getTagCloud($limit='', $order='title', $sort='asc', $checkAccess = false, $search = '', $categoryBased = false)
	{
		$db = EB::db();
		$my = JFactory::getUser();

		$isBloggerMode = EBR::isBloggerMode();
		$queryExclude = '';
		$excludeCats = array();

		$query  =   'select a.`id`, a.`title`, a.`alias`, a.`created`, count(c.`id`) as `post_count`';
		$query	.=  ' from #__easyblog_tag as a';
		$query	.=  '    left join #__easyblog_post_tag as b';
		$query	.=  '    on a.`id` = b.`tag_id`';
		$query  .=  '    left join #__easyblog_post as c';
		$query  .=  '    on b.post_id = c.id';
		$query  .=  '    and c.`published` = ' . $db->Quote(EASYBLOG_POST_PUBLISHED);
		$query  .=  '    and c.`state` = ' . $db->Quote(EASYBLOG_POST_NORMAL);

		if ($checkAccess) {

			if ($my->id == 0) {
				$query  .=  '    and c.`access` = ' . $db->Quote(BLOG_PRIVACY_PUBLIC);
			}

			// category access here
			$catLib = EB::category();
			$catAccessSQL = $catLib->genAccessSQL( 'c.`id`');

			$query .= ' AND (' . $catAccessSQL . ')';
		}

		if ($categoryBased) {
			$query .=  '    and c.`category_id` = ' . $db->Quote($categoryBased);
		}

		if ($isBloggerMode !== false) {
			$query  .=  '    and c.`created_by` = ' . $db->Quote($isBloggerMode);
		}

		$query  .= 	' where a.`published` = ' . $db->Quote('1');

		// Ensure that the count also respects the search
		if (!empty($search)) {
			$query	.= ' AND a.`title` LIKE ' . $db->Quote( '%' . $search . '%');
		}



		$query	.=  ' group by (a.`id`)';

		//order
		if ($order == 'created') {
			$query .= ' ORDER BY `created`';
		}

		if ($order == 'postcount') {
			$query	.=  ' order by `post_count`';
		}

		if ($order == 'title' || !$order) {
			$query	.=  ' order by a.`title`';
		}

		//sort
		if ($sort == 'asc') {
			$query .= ' ASC ';
		} else {
			$query .= ' DESC ';
		}

		//limit
		if (!empty($limit)) {
			$query	.=  ' LIMIT ' . (INT)$limit;
		}

		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
	}


	public function getTags($count="")
	{
		$db = EB::db();

		$query = ' SELECT `id`, `title`, `alias` ';
		$query .= ' FROM #__easyblog_tag ';
		$query .= ' WHERE `published` = 1 ';
		$query .= ' ORDER BY `title`';

		if (!empty($count)) {
			$query .= ' LIMIT ' . $count;
		}

		$db->setQuery($query);
		$result = $db->loadObjectList();

		return $result;
	}

	/**
	 * Searches for a tags given a specific keyword
	 *
	 * @since	4.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function search($keyword, $wordSearch = true, $limit = null)
	{
		$db = EB::db();

		$query = array();

		$search = $wordSearch ? '%' . $keyword . '%' : $keyword . '%';

		$query[] = 'SELECT * FROM ' . $db->quoteName('#__easyblog_tag');
		$query[] = 'WHERE ' . $db->quoteName('title') . ' LIKE ' . $db->Quote($search);
		$query[] = 'AND ' . $db->quoteName('published') . '=' . $db->Quote(1);

		if ($limit) {
			$query[] = 'LIMIT ' . $limit;
		}

		$query = implode(' ', $query);
		$db->setQuery($query);
		$result = $db->loadObjectList();

		return $result;
	}


	/**
	 * Searches for a tags given a specific keyword
	 *
	 * @since	4.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function suggest($keyword, $limit = null)
	{
		return $this->search($keyword, false, $limit);
	}

	/**
	 * *********************************************************************
	 * These part of codes will used in tag clod tags.
	 * *********************************************************************
	 */


	public function _buildQueryByTagBlogs()
	{
		$db			= EB::db();

		$query	=  'select count(a.`tag_id`) as `cnt`, b.*';
		$query	.= ' from `#__easyblog_post_tag` as a';
		$query	.= '   inner join `#__easyblog_post` as b on a.`post_id` = b.`id`';
		$query	.= ' group by (a.`post_id`)';
		$query	.= ' order by `cnt` desc';

		return $query;
	}


	public function getTagBlogs()
	{
		$db = EB::db();

		$query  = $this->_buildQueryByTagBlogs();
		$pg		= $this->getPaginationByTagBlogs();
		//$db->setQuery($query);

		$result = $this->_getList($query, $pg->limitstart, $pg->limit);

		return $result;
	}

	public function getPaginationByTagBlogs()
	{
		jimport('joomla.html.pagination');
		$this->_pagination	= EB::pagination( $this->getTotalByTagBlogs(), $this->getState('limitstart'), $this->getState('limit') );
		return $this->_pagination;
	}

	public function getTotalByTagBlogs()
	{
		// Lets load the content if it doesn't already exist
		$query = $this->_buildQueryByTagBlogs();
		$total = $this->_getListCount($query);

		return $total;
	}

	public function getTeamBlogCount( $tagId )
	{
		$db		= EB::db();
		$my		= JFactory::getUser();
		$config = EB::config();


		$isBloggerMode  = EasyBlogRouter::isBloggerMode();
		$extraQuery     = '';

		$query	= 'select count(1) from `#__easyblog_post` as a';
		$query	.= '  inner join `#__easyblog_post_tag` as b';
		$query	.= '    on a.`id` = b.`post_id`';

		$query	.= ' where b.`tag_id` = ' . $db->Quote($tagId);
		$query .= '  and (a.`source_type` = ' . $db->Quote(EASYBLOG_POST_SOURCE_TEAM);

	    // contribution type sql
	    $contributor = EB::contributor();
	    $contributeSQL = '';
	    if( $config->get( 'main_includeteamblogpost' )) {
	      $contributeSQL .= $contributor::genAccessSQL(EASYBLOG_POST_SOURCE_TEAM, 'a', array('concateOperator'=>'AND'));
	    }

	    $contributeSQL .= ')';
		$query .= $contributeSQL;

		if($isBloggerMode !== false)
			$query	.= '  and a.`created_by` = ' . $db->Quote($isBloggerMode);


		$db->setQuery($query);
		$result = $db->loadResult();

		return (empty($result)) ? '0' : $result;
	}

	/**
	 * *********************************************************************
	 * These part of codes will used in tag clod tags.
	 * *********************************************************************
	 */

	public function getTagPrivateBlogCount($tagId)
	{
		$db = EB::db();

		$isBloggerMode  = EasyBlogRouter::isBloggerMode();

		$query	= 'select count(1) from `#__easyblog_post` as a';
		$query	.= '  inner join `#__easyblog_post_tag` as b';
		$query	.= '    on a.`id` = b.`post_id`';
		$query	.= '    and b.`tag_id` = ' . $db->Quote($tagId);
		$query	.= '  where a.`access` = ' . $db->Quote(BLOG_PRIVACY_PRIVATE);

		if($isBloggerMode !== false)
			$query	.= '  and a.`created_by` = ' . $db->Quote($isBloggerMode);

		// category access here
		$catLib = EB::category();
		$catAccessSQL = $catLib->genAccessSQL('a.`id`');
		$query .= ' AND (' . $catAccessSQL . ')';

		$db->setQuery($query);
		$result = $db->loadResult();

		return (empty($result)) ? '0' : $result;
	}


	/**
	 * Delete blog posts association with the tags
	 *
	 * @since	4.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function deleteAssociation($postId)
	{
		$db = EB::db();

		$query	= 'DELETE FROM ' . $db->nameQuote( '#__easyblog_post_tag' ) . ' '
				. 'WHERE ' . $db->nameQuote('post_id') . '=' . $db->Quote($postId);
		$db->setQuery($query);

		return $db->Query();
	}

	/**
	 * Retrieve a list of tags on the site
	 *
	 * @since	1.3
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getDefaultTagsTitle()
	{
		$db = EB::db();

		$query = 'SELECT ' . $db->quoteName('title') . ' FROM ' . $db->quoteName('#__easyblog_tag');
		$query .= ' WHERE ' . $db->quoteName('default') . '=' . $db->Quote(1);

		$db->setQuery($query);

		$result = $db->loadColumn();

		return $result;
	}

	public function getDefaultTags()
	{
		$db		= EB::db();

		$query	= 'SELECT `id` FROM `#__easyblog_tag` '
				. 'WHERE `default` = 1';

		$db->setQuery($query);

		$tags	= $db->loadResultArray();

		return $tags;
	}


	public function preloadByPosts(array $postIds)
	{
		$db = EB::db();

		$query = 'select a.*, b.`post_id` from `#__easyblog_tag` as a';
		$query .= ' inner join `#__easyblog_post_tag` as b on a.`id` = b.`tag_id`';
		$query .= ' where b.`post_id` IN ( ' . implode(',' , $postIds) . ')';

		$db->setQuery($query);
		$results = $db->loadObjectList();

		return $results;
	}


}
