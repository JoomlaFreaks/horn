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
?>
<div class="row">
	<div class="col-lg-6">
		<div class="panel">
			<?php echo $this->html('panel.heading', 'COM_EASYBLOG_SETTINGS_LAYOUT_TOOLBAR_GENERAL', 'COM_EASYBLOG_SETTINGS_LAYOUT_TOOLBAR_GENERAL_INFO'); ?>

			<div class="panel-body">
				<?php echo $this->html('settings.toggle', 'layout_toolbar', 'COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_BLOG_TOOLBAR', '', 'data-layout-toolbar'); ?>

				<?php echo $this->html('settings.toggle', 'layout_headers', 'COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_BLOG_HEADER'); ?>

				<?php echo $this->html('settings.toggle', 'layout_header_description', 'COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_BLOG_DESCRIPTIONS_HEADER'); ?>

				<?php echo $this->html('settings.toggle', 'layout_headers_respect_author', 'COM_EASYBLOG_SETTINGS_LAYOUT_HEADER_RESPECT_AUTHOR'); ?>

				<?php echo $this->html('settings.toggle', 'layout_headers_respect_teamblog', 'COM_EASYBLOG_SETTINGS_LAYOUT_HEADER_RESPECT_TEAMBLOG'); ?>
			</div>
		</div>


	</div>

	<div class="col-lg-6">

		<div class="panel <?php echo !$this->config->get('layout_toolbar') ? 'hide' : '';?>" data-layout-toolbar-items>
			<?php echo $this->html('panel.heading', 'COM_EASYBLOG_SETTINGS_TOOLBAR_FRONTEND', 'COM_EASYBLOG_SETTINGS_TOOLBAR_FRONTEND_INFO'); ?>

			<div class="panel-body">
				<?php echo $this->html('settings.toggle', 'layout_latest', 'COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_LATEST_POST'); ?>

				<?php echo $this->html('settings.toggle', 'layout_option_toolbar', 'COM_EASYBLOG_SETTINGS_LAYOUT_BUTTON_IN_TOOLBAR'); ?>

				<?php echo $this->html('settings.toggle', 'layout_categories', 'COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_CATEGORIES'); ?>

				<?php echo $this->html('settings.toggle', 'layout_tags', 'COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_TAGS'); ?>

				<?php echo $this->html('settings.toggle', 'layout_bloggers', 'COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_BLOGGERS'); ?>

				<?php echo $this->html('settings.toggle', 'layout_search', 'COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_SEARCH'); ?>

				<?php echo $this->html('settings.toggle', 'layout_teamblog', 'COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_TEAMBLOG'); ?>

				<?php echo $this->html('settings.toggle', 'layout_archives', 'COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_ARCHIVES'); ?>

				<?php echo $this->html('settings.toggle', 'layout_calendar', 'COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_CALENDAR'); ?>

				<?php echo $this->html('settings.toggle', 'layout_login', 'COM_EASYBLOG_SETTINGS_LAYOUT_ENABLE_LOGIN'); ?>

				<?php echo $this->html('settings.toggle', 'toolbar_editprofile', 'COM_EASYBLOG_SETTINGS_LAYOUT_TOOLBAR_SHOW_EDIT_PROFILE'); ?>

				<?php echo $this->html('settings.toggle', 'toolbar_teamrequest', 'COM_EASYBLOG_SETTINGS_LAYOUT_TOOLBAR_SHOW_TEAM_REQUEST'); ?>

				<?php echo $this->html('settings.toggle', 'toolbar_logout', 'COM_EASYBLOG_SETTINGS_LAYOUT_TOOLBAR_SHOW_LOGOUT'); ?>
			</div>
		</div>
	</div>
</div>
