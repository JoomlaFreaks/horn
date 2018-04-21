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
?>
<form name="adminForm" action="index.php" method="post" class="adminForm" id="adminForm">
	<div class="row">

		<div class="col-lg-6">
			<div class="panel">
				<?php echo $this->html('panel.heading', 'COM_EASYBLOG_AUTOPOST_FACEBOOK_APP_SETTINGS', 'COM_EASYBLOG_AUTOPOST_FACEBOOK_APP_INFO'); ?>

				<div class="panel-body">
					<?php echo $this->html('settings.toggle', 'integrations_facebook', 'COM_EASYBLOG_AUTOPOST_FACEBOOK_ENABLE'); ?>

					<div class="form-group" data-facebook-api>
						<?php echo $this->html('form.label', 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_APP_ID', 'integrations_facebook_api_key'); ?>

						<div class="col-md-7">
							<div class="input-group input-group-link">
								<input type="text" name="integrations_facebook_api_key" class="form-control" value="<?php echo $this->config->get('integrations_facebook_api_key');?>" size="60" />
								<span class="input-group-btn">
									<a href="https://stackideas.com/docs/easyblog/administrators/autoposting/facebook-autoposting" target="_blank" class="btn btn-default">
										<i class="fa fa-life-ring"></i>
									</a>
								</span>
							</div>
						</div>
					</div>

					<div class="form-group" data-facebook-secret>
						<?php echo $this->html('form.label', 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_SECRET_KEY', 'integrations_facebook_secret_key'); ?>

						<div class="col-md-7">
							<div class="form-inline">
								<div class="input-group input-group-link">
									<input type="text" name="integrations_facebook_secret_key" class="form-control" value="<?php echo $this->config->get('integrations_facebook_secret_key');?>" size="60" />
									<span class="input-group-btn">
										<a href="https://stackideas.com/docs/easyblog/administrators/autoposting/facebook-autoposting" target="_blank" class="btn btn-default">
											<i class="fa fa-life-ring"></i>
										</a>
									</span>
								</div>
							</div>
						</div>
					</div>

					<div class="form-group">
						<?php echo $this->html('form.label', 'COM_EASYBLOG_AUTOPOSTING_FACEBOOK_ACCESS', 'facebook_access'); ?>

						<div class="col-md-7">
							<?php if ($associated) { ?>
								<div>
									<div style="margin-top:5px;">
										<?php echo $client->getRevokeButton(rtrim(JURI::root(), '/') . '/administrator/index.php?option=com_easyblog&view=autoposting&layout=facebook', true);?>
									</div>

									<div style="margin:15px 0 8px 0;border: 1px dashed #d7d7d7;padding: 20px;">
										<p>
											<?php echo JText::_('COM_EASYBLOG_FACEBOOK_EXPIRE_TOKEN');?> <b><?php echo $expire; ?></b>.
										</p>
									</div>
								</div>
							<?php } else { ?>
								<?php echo $client->getLoginButton(rtrim(JURI::root(), '/') . '/administrator/index.php?option=com_easyblog&view=autoposting&layout=facebook', true);?>

								<div class="mt-10">
									<?php echo JText::_('COM_EASYBLOG_INTEGRATIONS_FACEBOOK_ACCESS_DESC');?>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>

			<div class="panel">
				<?php echo $this->html('panel.heading', 'COM_EASYBLOG_AUTOPOST_FACEBOOK_PAGES', 'COM_EASYBLOG_AUTOPOST_FACEBOOK_PAGES_INFO'); ?>

				<div class="panel-body">
					<?php if ($associated) { ?>
						<?php echo $this->html('settings.toggle', 'integrations_facebook_impersonate_page', 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_PAGE_IMPERSONATION'); ?>

						<div class="form-group">
							<?php echo $this->html('form.label', 'COM_EASYBLOG_INTEGRATIONS_FACEBOOK_SELECT_PAGE', 'integrations_facebook_page_id'); ?>

							<div class="col-md-7">
								<?php if ($pages) { ?>
								<select name="integrations_facebook_page_id[]" id="integrations_facebook_page_id" class="form-control" multiple="multiple">
									<?php foreach ($pages as $page) { ?>
									<option value="<?php echo $page->id;?>" <?php echo ($storedPages && in_array($page->id, $storedPages)) ? ' selected="selected"' : '';?>>
										<?php echo $page->name;?>
									</option>
									<?php } ?>
								</select>
								<?php } ?>
							</div>
						</div>
					<?php } else { ?>
					<div class="form-group">
						<div>
							<?php echo JText::_('COM_EASYBLOG_AUTOPOSTING_FACEBOOK_PAGES_UNAVAILABLE');?>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>

			<div class="panel">
				<?php echo $this->html('panel.heading', 'COM_EASYBLOG_AUTOPOST_FACEBOOK_GROUPS', 'COM_EASYBLOG_AUTOPOST_FACEBOOK_GROUPS_INFO'); ?>

				<div class="panel-body">
					<?php if ($associated) { ?>
						<?php echo $this->html('settings.toggle', 'integrations_facebook_impersonate_group', 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_GROUP'); ?>

						<div class="form-group">
							<?php echo $this->html('form.label', 'COM_EASYBLOG_INTEGRATIONS_FACEBOOK_SELECT_GROUPS', 'integrations_facebook_group_id'); ?>
							
							<div class="col-md-7">
								<?php if ($groups) { ?>
								<select name="integrations_facebook_group_id[]" id="integrations_facebook_group_id" class="form-control" multiple="multiple" size="10">
									<?php foreach ($groups as $group) { ?>
									<option value="<?php echo $group->id;?>" <?php echo in_array($group->id, $storedGroups) ? ' selected="selected"' : '';?>>
										<?php echo $group->name;?>
									</option>
									<?php } ?>
								</select>
								<?php } ?>
							</div>
						</div>
					<?php } else { ?>
					<div class="form-group">
						<?php echo JText::_('COM_EASYBLOG_AUTOPOSTING_FACEBOOK_GROUPS_UNAVAILABLE');?>
					</div>
					<?php } ?>
				</div>
			</div>

		</div>

		<div class="col-lg-6">
			<div class="panel">
				<div class="panel-head">
					<b><?php echo JText::_('COM_EASYBLOG_AUTOPOST_FACEBOOK_APP_GENERAL'); ?></b>
					<div class="panel-info"><?php echo JText::_('COM_EASYBLOG_AUTOPOST_FACEBOOK_APP_GENERAL_INFO'); ?></div>
				</div>

				<div class="panel-body">
					<?php echo $this->html('settings.toggle', 'integrations_facebook_centralized', 'COM_EASYBLOG_AUTOPOSTING_CENTRALIZED'); ?>

					<?php echo $this->html('settings.toggle', 'integrations_facebook_centralized_auto_post', 'COM_EASYBLOG_AUTOPOST_ON_NEW_POST'); ?>

					<?php echo $this->html('settings.toggle', 'integrations_facebook_centralized_send_updates', 'COM_EASYBLOG_AUTOPOST_ON_UPDATES'); ?>

					<?php echo $this->html('settings.toggle', 'integrations_facebook_centralized_and_own', 'COM_EASYBLOG_FACEBOOK_ALLOW_AUTHOR_USE_OWN_FACEBOOK_ACCOUNT'); ?>

					<div class="form-group">
						<?php echo $this->html('form.label', 'COM_EASYBLOG_INTEGRATIONS_FACEBOOK_CONTENT_FROM', 'integrations_facebook_source'); ?>

						<div class="col-md-7">
							<select name="integrations_facebook_source" id="integrations_facebook_source" class="form-control">
								<option value="intro"<?php echo $this->config->get('integrations_facebook_source') == 'intro' ?  ' selected="selected"' : '';?>><?php echo JText::_( 'COM_EASYBLOG_INTROTEXT' ); ?></option>
								<option value="content"<?php echo $this->config->get('integrations_facebook_source') == 'content' ?  ' selected="selected"' : '';?>><?php echo JText::_( 'COM_EASYBLOG_CONTENT' ); ?></option>
							</select>
						</div>
					</div>

					<div class="form-group">
						<?php echo $this->html('form.label', 'COM_EASYBLOG_INTEGRATIONS_FACEBOOK_CONTENT_LENGTH', 'integrations_facebook_blogs_length'); ?>

						<div class="col-md-7">
							<div class="form-inline">
								<div class="form-group">
									<div class="input-group">
										<input type="text" name="integrations_facebook_blogs_length" id="integrations_facebook_blogs_length" class="form-control text-center" value="<?php echo $this->config->get('integrations_facebook_blogs_length');?>" size="5" />
										<span class="input-group-addon"><?php echo JText::_('COM_EASYBLOG_AUTOPOST_FACEBOOK_CHARACTERS');?></span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>

	<?php echo $this->html('form.action'); ?>
</form>
