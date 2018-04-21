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

require_once(dirname(__FILE__) . '/model.php');

class EasyBlogModelBloggers extends EasyBlogAdminModel
{
	public $total = null;
	public $pagination = null;
	public $data = null;

	public function __construct()
	{
		parent::__construct();

		$limit = $this->app->getUserStateFromRequest('com_easyblog.users.limit', 'limit', $this->app->getCfg('list_limit'), 'int');
		$limitstart = $this->input->get('limitstart', 0, 'int');

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}

	/**
	 * Retrieves a list of Joomla user groups from the site
	 *
	 * @since	5.1
	 * @access	public
	 */
	public function getJoomlaUserGroups($userId = '')
	{
		$db = EB::db();

		$query = 'SELECT a.id, a.title AS `name`, COUNT(DISTINCT b.id) AS level';
		$query .= ' , GROUP_CONCAT(b.id SEPARATOR \',\') AS parents';
		$query .= ' FROM #__usergroups AS a';
		$query .= ' LEFT JOIN `#__usergroups` AS b ON a.lft > b.lft AND a.rgt < b.rgt';

		// condition
		$where  = array();

		if (!empty($cid)) {
			$where[] = ' a.`id` = ' . $db->quote($cid);
		}

		$where = count($where) ? ' WHERE ' .implode(' AND ', $where) : '';

		$query .= $where;

		// grouping and ordering
		$query .= ' GROUP BY a.id';
		$query .= ' ORDER BY a.lft ASC';

		$db->setQuery($query);
		$result = $db->loadObjectList();

		return $result;
	}

	/**
	 * Method to get the total nr of the categories
	 *
	 * @access public
	 * @return integer
	 */
	public function getTotal()
	{
		// Lets load the content if it doesn't already exist
		if (!$this->total) {
			$query = $this->_buildQuery();
			$this->total = $this->_getListCount($query);
		}

		return $this->total;
	}

	/**
	 * Method to get a pagination object for the categories
	 *
	 * @access public
	 * @return integer
	 */
	public function getPagination()
	{
		// Lets load the content if it doesn't already exist
		if (!$this->pagination) {
			jimport('joomla.html.pagination');
			$this->pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->pagination;
	}

	/**
	 * Method to build the query for the tags
	 *
	 * @access private
	 * @return string
	 */
	public function _buildQuery($browsingMode = false, $exclusion = array(), $isCount = false)
	{
		// Get the WHERE and ORDER BY clauses for the query
		$where = $this->_buildQueryWhere($browsingMode, $exclusion);
		$orderby = $this->_buildQueryOrderBy();

		$db = EB::db();

		if ($browsingMode) {

			$query = array();
	 		$query[] = 'SELECT a.*, b.`content_id` AS `featured` FROM ' . $db->qn( '#__users' ) . ' AS a ';
	 		$query[] = 'LEFT JOIN ' . $db->qn( '#__easyblog_featured' ) . ' AS b ';
			$query[] = 'ON a.`id` = b.`content_id` AND b.`type`=' . $db->Quote( 'blogger' );
			$query[] = $where;
	 		$query[] = $orderby;

	 		$query = implode(' ', $query);

 		} else {

 			$query  = "select count( p.id ) as `totalPost`, COUNT( DISTINCT(g.content_id) ) as `featured`,";
			$query .= " a.*";

			if ($isCount) {
				$query  = "select count(a.id)";
			}

			$query .= "	from `#__users` as a";
			$query .= "	inner join `#__user_usergroup_map` as up on a.`id` = up.`user_id`";
			$query .= "	inner join `#__easyblog_acl_group` as ag  on up.group_id = ag.content_id";
			$query .= "	inner join `#__easyblog_acl` as acl on ag.`acl_id` = acl.`id`";

			$query .= " 	left join `#__easyblog_post` as p on a.`id` = p.`created_by`";
			$query .= " 		and `p`.`published` = " . $db->Quote(EASYBLOG_POST_PUBLISHED);
			$query .= " 		and p.`state` = " . $db->Quote(EASYBLOG_POST_NORMAL);
			$query .= " 	left join `#__easyblog_featured` AS `g` ON a.`id`= g.`content_id` AND g.`type`= " . $db->Quote('blogger');

			$query .= "	where acl.`action` = " . $db->Quote('add_entry');
			$query .= "	and ag.type = " . $db->Quote('group');
			$query .= "	and ag.status = " . $db->Quote('1');
			$query .= $where;
			$query .= " group by a.`id`";
			$query .= $orderby;

		}

		return $query;
	}

	/**
	 * Builds the where clause
	 *
	 * @since	5.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function _buildQueryWhere($browsingMode = false, $exclusion = array())
	{
		$db = EB::db();

		$filter_state = $this->app->getUserStateFromRequest('com_easyblog.users.filter_state', 'filter_state', '', 'word' );
		$search = $this->app->getUserStateFromRequest('com_easyblog.users.search', 'search', '', 'string');
		$search = $db->getEscaped(trim(JString::strtolower($search)));

		$where = array();

		if ($filter_state == 'P') {
			$where[] = $db->qn( 'a.block' ) . '=' . $db->Quote( '0' );
		}

		if ($filter_state == 'U') {
			$where[] = $db->qn( 'a.block' ) . '=' . $db->Quote( '1' );
		}

		if ($search) {
			$where[] = '(LOWER( a.name ) LIKE \'%' . $search . '%\' OR LOWER( a.username ) LIKE \'%' . $search . '%\') ';
		}

		if (!empty($exclusion)) {
			$exclusion = implode(',', $exclusion);
			$where[] = $db->qn('a.id') . ' NOT IN(' . $db->Quote($exclusion) . ')';
		}

		// $where = ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
		if ($where) {
			if ($browsingMode) {
				$where = (count($where)) ? ' where '.implode( ' AND ', $where ) : '';
			} else {
				$where = (count($where) > 1) ? ' AND '.implode( ' AND ', $where ) : ' AND ' . $where[0];
			}

			return $where;
		}

		return '';
	}

	/**
	 * Constructs the group by statement
	 *
	 * @since	5.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function _buildQueryGroupBy()
	{
		$db = EB::db();
		$query = 'GROUP BY a.' . $db->qn('id');

		return $query;
	}

	/**
	 * Constructs the order by clause
	 *
	 * @since	5.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function _buildQueryOrderBy()
	{
		$ordering = $this->app->getUserStateFromRequest('com_easyblog.users.filter_order', 'filter_order', 'a.id', 'cmd');
		$direction = $this->app->getUserStateFromRequest('com_easyblog.users.filter_order_Dir', 'filter_order_Dir', 'asc', 'word');

		$query = 'ORDER BY ' . $ordering . ' ' . $direction;

		return $query;
	}

	/**
	 * Retrieves a list of users
	 *
	 * @since	5.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getUsers($isBrowse = false, $exclusion = array())
	{
		// Lets load the content if it doesn't already exist
		if (!$this->data) {
			$db = EB::db();

			$query = $this->_buildQuery($isBrowse, $exclusion);
			$cntQuery = $this->_buildQuery($isBrowse, $exclusion, true);

			$this->total = $this->_getListCount($cntQuery);
			$this->pagination = new JPagination($this->total, $this->getState('limitstart'), $this->getState('limit'));

			// apply limit
			$query .= " LIMIT " . $this->getState('limitstart') . ', ' . $this->getState('limit');

			// lets get data
			$db->setQuery($query);
			$this->data = $db->loadObjectList();
		}

		return $this->data;
	}


	/**
	 * Method to publish or unpublish categories
	 *
	 * @access public
	 * @return array
	 */
	public function publish(&$categories = array(), $state = 1)
	{
		if (!$categories) {
			return false;
		}

		if (!is_array($categories)) {
			$categories = array($categories);
		}

		$categories = implode(',', $categories);

		$db = EB::db();

		$query = array();
		$query[] = 'UPDATE ' . $db->qn('#__easyblog_category');
		$query[] = 'SET ' . $db->qn('published') . '=' . $db->Quote($state);
		$query[] = 'WHERE ' . $db->qn('id') . ' IN(' . $categories . ')';

		$query = implode(' ', $query);

		$db->setQuery($query);

		return $db->Query();
	}
}
