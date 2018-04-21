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
			<?php echo $this->html('panel.heading', 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_EASYSOCIAL_GENERAL'); ?>

			<div class="panel-body">
				<?php echo $this->html('panel.info', 'COM_EASYBLOG_EASYSOCIAL_INFO', 'https://stackideas.com/easysocial?from=easyblog', 'COM_EASYBLOG_SIGNUP_WITH_EASYSOCIAL', 'btn-sm', JURI::base() . 'components/com_easyblog/themes/default/images/vendors/easysocial.png'); ?>

				<?php echo $this->html('settings.toggle', 'integrations_easysocial_miniheader', 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_EASYSOCIAL_MINI_HEADER'); ?>

				<?php echo $this->html('settings.toggle', 'integrations_easysocial_badges', 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_EASYSOCIAL_ACHIEVEMENTS'); ?>

				<?php echo $this->html('settings.toggle', 'integrations_easysocial_conversations', 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_EASYSOCIAL_CONVERSATIONS'); ?>

				<?php echo $this->html('settings.toggle', 'integrations_easysocial_points', 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_EASYSOCIAL_POINTS'); ?>

				<?php echo $this->html('settings.toggle', 'integrations_easysocial_friends', 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_EASYSOCIAL_FRIENDS'); ?>

				<?php echo $this->html('settings.toggle', 'integrations_easysocial_followers', 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_EASYSOCIAL_FOLLOW'); ?>

				<?php echo $this->html('settings.toggle', 'integrations_easysocial_editprofile', 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_EASYSOCIAL_PROFILE_MODIFY_EDIT_PROFILE_LINK'); ?>
			</div>
		</div>
	</div>

	<div class="col-lg-6">
		<div class="panel">
			<?php echo $this->html('panel.heading', 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_EASYSOCIAL_NOTIFICATIONS_TITLE', 'COM_EASYBLOG_EASYSOCIAL_NOTIFICATIONS_INFO'); ?>

			<div class="panel-body">
				<?php echo $this->html('settings.toggle', 'integrations_easysocial_notifications_newpost', 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_EASYSOCIAL_NOTIFICATIONS_NEW_POST'); ?>
				<?php echo $this->html('settings.toggle', 'integrations_easysocial_notifications_newcomment', 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_EASYSOCIAL_NOTIFICATIONS_NEW_COMMENT'); ?>
				<?php echo $this->html('settings.toggle', 'integrations_easysocial_notifications_ratings', 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_EASYSOCIAL_NOTIFICATIONS_NEW_RATING'); ?>
			</div>
		</div>

		<div class="panel">
			<?php echo $this->html('panel.heading', 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_EASYSOCIAL_SEARCH'); ?>

			<div class="panel-body">
				<?php echo $this->html('settings.toggle', 'integrations_easysocial_indexer_newpost', 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_EASYSOCIAL_INDEXER_INDEX_NEW_POST'); ?>

				<div class="form-group">
					<?php echo $this->html('form.label', 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_EASYSOCIAL_INDEXER_INDEX_NEW_POST_LENGTH', 'integrations_easysocial_indexer_newpost_length'); ?>

					<div class="col-md-7">
						<input type="text" name="integrations_easysocial_indexer_newpost_length" id="integrations_easysocial_indexer_newpost_length" class="form-control input-mini text-center" 
							value="<?php echo $this->config->get('integrations_easysocial_indexer_newpost_length');?>" />
						<span><?php echo JText::_( 'COM_EASYBLOG_CHARACTERS' ); ?></span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
