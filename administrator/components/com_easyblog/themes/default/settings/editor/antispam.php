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
			<?php echo $this->html('panel.heading', 'COM_EASYBLOG_SETTINGS_ANTI_SPAM', 'COM_EASYBLOG_SETTINGS_ANTI_SPAM_INFO'); ?>

			<div class="panel-body">
				<?php echo $this->html('settings.toggle', 'main_post_min', 'COM_EASYBLOG_SETTINGS_ENABLE_MINIMUM_CONTENT_LENGTH'); ?>

				<div class="form-group">
					<?php echo $this->html('form.label', 'COM_EASYBLOG_SETTINGS_MINIMUM_CONTENT_LENGTH', 'main_post_length'); ?>

					<div class="col-md-7">
						<div class="form-inline">
							<div class="form-group">
								<div class="input-group">
									<input type="text" name="main_post_length" id="main_post_length" value="<?php echo $this->escape( $this->config->get( 'main_post_length' ) );?>" class="form-control text-center" />
									<span class="input-group-addon"><?php echo JText::_('COM_EASYBLOG_CHARACTERS');?></span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-6">
		<div class="panel">
			<?php echo $this->html('panel.heading', 'COM_EASYBLOG_SETTINGS_CONTENT_FILTERING', 'COM_EASYBLOG_SETTINGS_CONTENT_FILTERING_INFO'); ?>

			<div class="panel-body">
				<p><?php echo JText::_('COM_EASYBLOG_SETTINGS_BLOCKED_WORDS_INFO'); ?></p>

				<div class="form-group">
					<?php echo $this->html('form.label', 'COM_EASYBLOG_SETTINGS_BLOCKED_WORDS', 'main_blocked_words'); ?>

					<div class="col-md-7">
						<textarea class="form-control" name="main_blocked_words" id="main_blocked_words"><?php echo $this->config->get( 'main_blocked_words' ); ?></textarea>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>