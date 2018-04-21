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
				<?php echo $this->html('panel.heading', 'COM_EASYBLOG_AUTOPOST_TWITTER_APP_SETTINGS', 'COM_EASYBLOG_AUTOPOST_TWITTER_APP_SETTINGS_INFO'); ?>

				<div class="panel-body">
					<?php echo $this->html('settings.toggle', 'integrations_twitter', 'COM_EASYBLOG_AUTOPOST_TWITTER_ENABLE'); ?>

					<div class="form-group" data-twitter-api>
						<?php echo $this->html('form.label', 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_TWITTER_API_KEY', 'integrations_twitter_api_key'); ?>

						<div class="col-md-7">
							<div class="input-group input-group-link">
								<input type="text" name="integrations_twitter_api_key" id="integrations_twitter_api_key" class="form-control" value="<?php echo $this->config->get('integrations_twitter_api_key');?>" />
								<span class="input-group-btn">
									<a href="https://stackideas.com/docs/easyblog/administrators/autoposting/twitter-autoposting" target="_blank" class="btn btn-default">
										<i class="fa fa-life-ring"></i>
									</a>
								</span>
							</div>
						</div>
					</div>

					<div class="form-group" data-twitter-secret>
						<?php echo $this->html('form.label', 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_TWITTER_SECRET_KEY', 'integrations_twitter_secret_key'); ?>

						<div class="col-md-7">
							<div class="input-group input-group-link">
								<input type="text" name="integrations_twitter_secret_key" id="integrations_twitter_secret_key" class="form-control" value="<?php echo $this->config->get('integrations_twitter_secret_key');?>" size="60" />
								<span class="input-group-btn">
									<a href="https://stackideas.com/docs/easyblog/administrators/autoposting/twitter-autoposting" target="_blank" class="btn btn-default">
										<i class="fa fa-life-ring"></i>
									</a>
								</span>
							</div>
						</div>
					</div>

					<div class="form-group">
						<?php echo $this->html('form.label', 'COM_EASYBLOG_AUTOPOSTING_TWITTER_ACCESS', 'twitter_access'); ?>

						<div class="col-md-7">
							<?php if ($associated) { ?>
								<?php echo $client->getRevokeButton(rtrim(JURI::root(), '/') . '/administrator/index.php?option=com_easyblog&view=autoposting&layout=twitter', true);?>
							<?php } else { ?>
								<?php echo $client->getLoginButton(rtrim(JURI::root(), '/') . '/administrator/index.php?option=com_easyblog&view=autoposting&layout=twitter', true);?>
								<div class="mt-10">
									<?php echo JText::_('COM_EASYBLOG_INTEGRATIONS_TWITTER_ACCESS_DESC');?>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>

			<div class="panel">
				<?php echo $this->html('panel.heading', 'COM_EASYBLOG_AUTOPOST_TWITTER_URL_SHORTENER', 'COM_EASYBLOG_AUTOPOST_TWITTER_URL_SHORTENER_INFO'); ?>

				<div class="panel-body">
					<?php echo $this->html('settings.toggle', 'integrations_twitter_shorten_url', 'COM_EASYBLOG_AUTOPOST_TWITTER_URL_SHORTENER_ENABLE'); ?>

					<div class="form-group" data-twitter-secret>
						<?php echo $this->html('form.label', 'COM_EASYBLOG_AUTOPOST_TWITTER_URL_SHORTENER_APIKEY', 'integrations_twitter_urlshortener_apikey'); ?>
						<div class="col-md-7">
							<div class="input-group input-group-link">
								<input type="text" name="integrations_twitter_urlshortener_apikey" id="integrations_twitter_urlshortener_apikey" class="form-control" value="<?php echo $this->config->get('integrations_twitter_urlshortener_apikey');?>" size="60" />
								<span class="input-group-btn">
									<a href="https://stackideas.com/docs/easyblog/administrators/autoposting/setting-up-twitter-autoposting" target="_blank" class="btn btn-default">
										<i class="fa fa-life-ring"></i>
									</a>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>

		<div class="col-lg-6">
			<div class="panel">
				<?php echo $this->html('panel.heading', 'COM_EASYBLOG_AUTOPOST_TWITTER', 'COM_EASYBLOG_AUTOPOST_TWITTER_INFO'); ?>
				
				<div class="panel-body">
					<?php echo $this->html('settings.toggle', 'integrations_twitter_centralized', 'COM_EASYBLOG_AUTOPOSTING_CENTRALIZED'); ?>

					<?php echo $this->html('settings.toggle', 'integrations_twitter_centralized_auto_post', 'COM_EASYBLOG_AUTOPOST_ON_NEW_POST'); ?>

					<?php echo $this->html('settings.toggle', 'integrations_twitter_centralized_send_updates', 'COM_EASYBLOG_AUTOPOST_ON_UPDATES'); ?>

					<?php echo $this->html('settings.toggle', 'integrations_twitter_centralized_and_own', 'COM_EASYBLOG_TWITTER_ALLOW_AUTHOR_USE_OWN_ACCOUNT'); ?>

					<?php echo $this->html('settings.toggle', 'integrations_twitter_upload_image', 'COM_EASYBLOG_TWITTER_AUTOPOST_UPLOAD_IMAGE'); ?>

					<div class="form-group">
						<?php echo $this->html('form.label', 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_TWITTER_DEFAULT_MESSAGE', 'main_twitter_message'); ?>

						<div class="col-md-7">
							<textarea name="main_twitter_message" id="main_twitter_message" class="form-control"><?php echo $this->config->get('main_twitter_message', JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_TWITTER_DEFAULT_MESSAGE_STRING'));?></textarea>
							<br />
							<div><?php echo JText::_('COM_EASYBLOG_SETTINGS_SOCIALSHARE_TWITTER_MESSAGE_DESC');?></div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>

	<?php echo $this->html('form.action'); ?>
</form>
