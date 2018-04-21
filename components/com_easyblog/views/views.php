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

jimport('joomla.application.component.view');
jimport('joomla.filesystem.folder');

class EasyBlogView extends JViewLegacy
{
	protected $app = null;
	protected $my = null;
	protected $customTheme = null;
	protected $props = array();
	public $paramsPrefix = 'listing';

	public function __construct()
	{
		$this->doc = JFactory::getDocument();
		$this->app = JFactory::getApplication();
		$this->my = JFactory::getUser();
		$this->config = EB::config();
		$this->info = EB::info();
		$this->jconfig = EB::jconfig();
		$this->acl = EB::acl();

		// If this is a dashboard theme, we need to let the theme object know
		$options = array('paramsPrefix' => $this->paramsPrefix);

		// If this is an ajax document, we should pass the $ajax library to the client
		if ($this->doc->getType() == 'ajax') {

			// We need to load frontend language from here incase it was called from backend.
			EB::loadLanguages();

			$this->ajax = EB::ajax();
		}

		// Create an instance of the theme so child can start setting variables to it.
		$this->theme = EB::template(null, $options);

		// Set the input object
		$this->input = EB::request();
	}

	/**
	 * Allows child to set variables
	 *
	 * @since   5.1
	 * @access  public
	 */
	public function set($key, $value = '')
	{
		if ($this->doc->getType() == 'json') {
			$this->props[$key] = $value;

			return;
		}

		$this->theme->set($key, $value);
	}

	/**
	 * Allows children to check for acl
	 *
	 * @since   5.1
	 * @access  public
	 */
	public function checkAcl($rule, $default = null)
	{
		$allowed = $this->acl->get($rule, $default);

		if (!$allowed) {
			JError::raiseError(500, JText::_('COM_EASYBLOG_NOT_ALLOWED_ACCESS_IN_THIS_SECTION'));
			return;
		}

		return true;
	}

	/**
	 * Responsible to render the css files on the head
	 *
	 * @since   5.1
	 * @access  public
	 */
	public function renderHeaders()
	{
		// Load js stuffs
		$view = $this->input->get('view', '', 'cmd');

		// Determines which js section to initialize
		$section = 'site';

		if ($view == 'composer' || $view == 'templates') {
			$section = 'composer';
		}

		EB::init($section);

		// Get the theme on the site
		$theme = $this->config->get('theme_site');

		if ($this->customTheme) {
			$theme = $this->customTheme;
		}

		// Attach the theme's css
		$stylesheet = EB::stylesheet($section, $theme);

		// Allow caller to invoke recompiling of the entire css
		if ($this->input->get('compileCss') && EB::isSiteAdmin()) {
			$result = $stylesheet->build('full');

			header('Content-type: text/x-json; UTF-8');
			echo json_encode($result);
			exit;
		}

		$stylesheet->attach(true, true, $this->customTheme);
	}

	/**
	 * Allows caller to set a custom theme
	 *
	 * @since   5.1
	 * @access  public
	 */
	public function setTheme($theme)
	{
		$this->customTheme = $theme;

		$this->theme->setCategoryTheme($theme);
	}

	/**
	 * Responsible to display the entire component output
	 *
	 * @since   5.1
	 * @access  public
	 */
	public function display($tpl = null)
	{
		// Response for json calls
		if ($this->doc->getType() == 'json') {

			$callback = $this->input->get('callback', '', 'cmd');
			$output = json_encode($this->props);

			if ($callback) {
				$output = $callback . '(' . $output . ')';
			}

			header('Content-type: text/x-json; UTF-8');
			echo $output;
			exit;
		}

		// Standard html response
		if ($this->doc->getType() == 'html') {

			$this->renderHeaders();

			// Get the contents from the view
			$namespace  = 'site/' . $tpl;

			$contents = $this->theme->output($namespace);

			// Get menu suffix
			$suffix = $this->getMenuSuffix();

			// Get the current view.
			$view = $this->getName();

			// Get the current task
			$layout = $this->getLayout();

			// If this is a dashboard theme, we need to let the theme object know
			$options = array();

			if ($this->getName() == 'dashboard') {
				$options['dashboard'] = true;
			}

			// We need to append the contents back into the main structure
			$theme = EB::template(null, $options);

			$tmpl = $this->input->get('tmpl');

			// Get the toolbar
			$toolbar = '';
			$contributionHeader = false;

			// Render EasyBlog's toolbar
			if ($tmpl != 'component') {
				$toolbar = $this->getToolbar();
			}

			if ($view == 'entry' && $layout != 'preview') {

				$id = $this->input->get('id', 0, 'int');
				$post = EB::post($id);

				if (!$post->isStandardSource()) {
					$contribution = $post->getBlogContribution();

					$contributionHeader = $contribution->getHeader();

					if ($contributionHeader) {
						$toolbar = '';
					}
				}
			}

			// Get the theme name
			$themeName = $theme->getName();

			// Push notifications
			if (EB::push()->isEnabled()) {
				EB::push()->generateScripts();
			}

			// We attach the script tags on the bottom of the page
			$scripts = EB::helper('Scripts')->getScripts();

			// Jomsocial toolbar
			$jsToolbar = EB::jomsocial()->getToolbar();
			$theme->set('jsToolbar', $jsToolbar);

			$lang = JFactory::getLanguage();
			$rtl = $lang->isRTL();

			// Load easysocial headers when viewing posts of another person
			$miniheader = '';

			$showMiniHeader = $this->config->get('integrations_easysocial_miniheader');

			// Only work for Easysocial 2.0. Only display if there is no contribution header.
			if ($showMiniHeader && $view == 'entry' && EB::easysocial()->exists() && !EB::easysocial()->isLegacy() && !$contributionHeader && $layout != 'preview') {
				ES::initialize();

				if (!isset($post)) {
					$id = $this->input->get('id', 0, 'int');
					$post = EB::post($id);
				}

				$user = ES::user($post->getAuthor()->id);

				$miniheader = ES::themes()->html('html.miniheader', $user);
			}

			// For image popups and container
			$loadImageTemplates = $view == 'composer' ? false : true;

			$theme->set('loadImageTemplates', $loadImageTemplates);
			$theme->set('miniheader', $miniheader);
			$theme->set('rtl', $rtl);
			$theme->set('bootstrap', '');
			$theme->set('themeName', $themeName);
			$theme->set('jscripts', $scripts);
			$theme->set('toolbar', $toolbar);
			$theme->set('contents', $contents);
			$theme->set('suffix', $suffix);
			$theme->set('layout', $layout);
			$theme->set('view', $view);

			$output = $theme->output('site/structure/default');

			echo $output;
			return;
		}
	}

	/**
	 * Sets view in breadcrumbs
	 *
	 * @since   5.1
	 * @access  public
	 */
	public function setViewBreadcrumb($view = null)
	{
		if (is_null($view)) {
			$view = $this->getName();
		}

		if (!EasyBlogRouter::isCurrentActiveMenu($view)) {
			$this->setPathway(JText::_('COM_EASYBLOG_BREADCRUMB_' . strtoupper($view)));

			return true;
		}

		return false;
	}

	/**
	 * Retrieves the toolbar for the site.
	 *
	 * @since   5.1
	 * @access  public
	 */
	public function getToolbar()
	{
		// Get the current view
		$view = $this->input->get('view', '', 'cmd');

		// Get a list of available views
		$views = JFolder::folders(JPATH_COMPONENT . '/views');

		// Get the active view name
		$active = $this->getName();

		// If the current active view doesn't exist on our known views, set the latest to be active by default.
		if (!in_array($active, $views)) {
			$active = 'latest';
		}

		// Rebuild the views
		$tmp = new stdClass();

		foreach ($views as $key) {
			$tmp->$key  = false;
		}

		// Reset back the views to the tmp variable
		$views = $tmp;

		// Set the active menu
		if (isset($views->$active)) {
			$views->$active = true;
		}

		// Get toolbar stuffs
		$title = $this->config->get('main_title');
		$desc = $this->config->get('main_description');
		$desc = nl2br($desc);
		$authorId = '';

		// Entry view, we want to load the toolbar
		if ($active == 'entry') {
			$blog = EB::table('Post');
			$blog->load(JRequest::getInt('id'));

			$authorId = $blog->created_by;
		}

		// Blogger view, just get the id from the query
		if ($active == 'blogger') {
			$authorId = $this->input->get('id', 0, 'int');
		}

		// If the viewer is viewing a blogger, we'll need to display the header accordingly.
		if (($active == 'blogger' || ($active == 'entry' && $this->config->get('layout_headers_respect_author'))) && $authorId) {

			$author = EB::user($authorId);

			$title = $author->title ? $author->title : $title;
			$desc = $author->getDescription() ? $author->getDescription() : $desc;
		}

		// If the viewer is viewing a team
		if ($active == 'teamblog' && $this->config->get('main_includeteamblogdescription') && $this->config->get('layout_headers_respect_teamblog')) {

			// Only process the header when the viewer is on listings layout.
			if (JRequest::getVar('layout') == 'listings') {
				$team = EB::table('Teamblog');
				$team->load(JRequest::getInt('id'));

				if ($team->includeTitleDesc()) {
					$title = $team->title ? JText::_($team->title) : $title;
					$desc = $team->getDescription() ? $team->getDescription() : $desc;
				}
			}
		}

		// Get the current menu id
		$itemId = $this->input->get('Itemid', 0, 'int');

		// Determines if the heading should be displayed
		$activeMenu = JFactory::getApplication()->getMenu()->getActive();
		$params = new JRegistry();

		if ($activeMenu) {
			$params = $activeMenu->params;
		}

		$heading = $params->get('show_page_heading', '');

		if ($heading) {
			$title = $params->get('page_heading', $title);
		}

		// Get the total subscribers on the site
		$model = EB::model('Subscription');

		// Load up the subscription record for the current user.
		$subscription = EB::table('Subscriptions');

		if (!$this->my->guest) {
			$subscription->load(array('email' => $this->my->email, 'utype' => 'site'));
		}

		// Determines if this should be on blogger mode
		$bloggerMode = EBR::isBloggerMode();

		// Build the return url
		$return = base64_encode(JURI::getInstance()->toString());

		// Get total pending blog posts
		$totalPending = 0;
		$totalTeamRequests = 0;
		$totalPendingComments = 0;

		if (EB::isSiteAdmin() || $this->acl->get('moderate_entry')) {
			$blogsModel = EB::model('Blogs');
			$totalPending = $blogsModel->getTotalPending();
		}

		// Get total team requests to join team.
		if (EB::isTeamAdmin()) {
			$teamModel = EB::model('TeamBlogs');
			$totalTeamRequests = $teamModel->getTotalRequest();
		}

		// Get total pending comments
		if (EB::isSiteAdmin() || $this->acl->get('moderate_comment')) {
			$commentModel = EB::model('Comments');
			$totalPendingComments = $commentModel->getTotalPending();
		}

		$showFooter = true;

		if (!$this->config->get('layout_latest') && !$this->config->get('layout_categories') && !$this->config->get('layout_tags')
			&& !$this->config->get('layout_bloggers') && !$this->config->get('layout_teamblog') && !$this->config->get('layout_archives')
			&& !$this->config->get('layout_calendar')) {
			$showFooter = false;
		}

		$showManage = false;

		if (EB::isSiteAdmin() || $this->acl->get('add_entry') || $this->acl->get('moderate_entry') || $this->acl->get('manage_comment') || $this->acl->get('create_category') || 
			$this->acl->get('create_tag') || $this->acl->get('create_team_blog')
		) {
			$showManage = true;
		}

		// Load the theme object
		$theme = EB::themes();
		$theme->set('showManage', $showManage);
		$theme->set('showFooter', $showFooter);
		$theme->set('totalPending', $totalPending);
		$theme->set('totalTeamRequests', $totalTeamRequests);
		$theme->set('totalPendingComments', $totalPendingComments);
		$theme->set('view', $view);
		$theme->set('subscription', $subscription);
		$theme->set('bloggerMode', $bloggerMode);
		$theme->set('heading', $heading);
		$theme->set('return', $return);
		$theme->set('title', $title);
		$theme->set('desc', $desc);
		$theme->set('views', $views);

		$output = $theme->output('site/toolbar/default');

		return $output;
	}

	/**
	 * Retrieve the menu suffix for a page
	 *
	 * @since   5.1
	 * @access  public
	 */
	public function getMenuSuffix()
	{
		$menu = $this->app->getMenu()->getActive();
		$suffix = '';

		if ($menu) {
			$suffix = $menu->params->get('pageclass_sfx', '');
		}

		return $suffix;
	}

	/**
	 * Generate a canonical tag on the header of the page
	 *
	 * @since   5.1
	 * @access  public
	 */
	public function canonical($url, $route = true)
	{
		if ($route) {
			$url = EBR::_($url, false, null, false, true);
		}

		$this->doc->addHeadLink($this->escape($url), 'canonical');
	}

	/**
	 * Generate a rel tag on the header of the page
	 *
	 * @since   5.1
	 * @access  public
	 */
	public function amp($url, $route = true)
	{
		if ($route) {
			$url = EBR::_($url, false, null, false, true);
		}

		$this->doc->addHeadLink($this->escape($url), 'amphtml');
	}

	/**
	 * Retrieves the active menu
	 *
	 * @since   5.0
	 * @access  public
	 */
	public function getActiveMenu()
	{
		return $this->app->getMenu()->getActive();
	}

	/**
	 * Retrieve any queued messages from the system
	 *
	 * @since   5.1
	 * @access  public
	 */
	public function getMessages()
	{
		$messages = EB::getMessageQueue();

		return $messages;
	}

	/**
	 * Adds the breadcrumbs on the site
	 *
	 * @since   5.1
	 * @access  public
	 */
	public function setPathway($title, $link ='')
	{
		// Get the pathway
		$pathway = $this->app->getPathway();

		// set this option to true if the breadcrumb didn't show the EasyBlog root menu.
		$showRootMenuItem = false;

		// Translate the pathway item
		$title = JText::_($title);
		$state = $pathway->addItem($title, $link);

		return $state;
	}

	/**
	 * Renders JSON output on the page
	 *
	 * @since	5.1
	 * @access	public
	 */
	protected function outputJSON($output = null)
	{
		echo '<script type="text/json" id="ajaxResponse">' . json_encode($output) . '</script>';
		exit;
	}

	/**
	 * Responsible to modify the title whenever necessary. Inherited classes should always use this method to set the title
	 *
	 * @since	5.0
	 * @access	public
	 */
	public function setPageTitle($title, $pagination = null , $addSitePrefix = false )
	{
		$page = null;

		if ($addSitePrefix) {
			$addTitle = $this->jconfig->get('sitename_pagetitles');
			$sitenameOrdering = $this->config->get('sitename_position', 'default');

			if ($sitenameOrdering == 'after' && $addTitle == 2) {
				// Only apply if the joomla site name setting is using 'after'
				$titleTmp = explode(" - ", $title);
				$title = $titleTmp[0] . ' - ' . JText::_($this->config->get('main_title')) . ' - ' . $titleTmp[1];
			} else {
				// Normal ordering
				$title .= ' - ' . JText::_($this->config->get('main_title'));
			}
		}

		if ($pagination && is_object($pagination)) {
			$page = $pagination->get('pages.current');

			// Append the current page if necessary.
			$title .= $page == 1 ? '' : ' - ' . JText::sprintf('COM_EASYBLOG_PAGE_NUMBER', $page);
		}

		$this->doc->setTitle($title);
	}

	/**
	 * Sets the rss author email
	 *
	 * @since   5.1
	 * @access  public
	 */
	public function getRssEmail($author)
	{
		if ($this->jconfig->get('feed_email') == 'none') {
			return;
		}

		if ($this->jconfig->get('feed_email') == 'author') {
			return $author->user->email;
		}

		return $this->jconfig->get('mailfrom');
	}
}
