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
<div id="eb" class="eb-mod mod_easyblogsubscribe<?php echo $modules->getWrapperClass();?>" data-eb-module-subscribe>
<?php if ($params->get('type' , 'link') == 'link') { ?>
	<a href="javascript:void(0);" data-blog-subscribe data-id="<?php echo $id;?>" data-type="<?php echo $type; ?>" class="btn btn-primary btn-block <?php echo $subscribed ? 'hide' : ''; ?>"><?php echo JText::_('MOD_SUBSCRIBE_MESSAGE_' . strtoupper($type));?></a>

	<a href="javascript:void(0);" data-blog-unsubscribe data-subscription-id="<?php echo $subscribed;?>" data-type="<?php echo $type; ?>" data-return="<?php echo $return;?>" class="btn btn-primary btn-block <?php echo $subscribed ? '' : 'hide'; ?>"><?php echo JText::_('MOD_UNSUBSCRIBE_MESSAGE_' . strtoupper($type));?></a>
<?php } else { ?>
	<form name="subscribe-blog" id="subscribe-blog-module" method="post" class="eb-mod-form">
		<div class="eb-mod-form-item form-group">
			<label for="eb-subscribe-fullname">
				<?php echo JText::_('MOD_EASYBLOGSUBSCRIBE_YOUR_NAME'); ?>
			</label>
			<input type="text" name="esfullname" class="form-control" id="eb-subscribe-fullname" data-eb-subscribe-name />
		</div>
		<div class="eb-mod-form-item form-group">
			<label for="eb-subscribe-email">
				<?php echo JText::_('MOD_EASYBLOGSUBSCRIBE_YOUR_EMAIL'); ?>
			</label>
			<input type="text" name="email" class="form-control" id="eb-subscribe-email" data-eb-subscribe-mail />
		</div>
		<div class="eb-mod-form-action">
			<a href="javascript:void(0);" class="btn btn-primary" data-subscribe-link><?php echo JText::_('MOD_SUBSCRIBE_MESSAGE_' . strtoupper($type));?></a>
		</div>
	</form>

	<script type="text/javascript">
	EasyBlog.ready(function($){

		$('[data-subscribe-link]').on("click", function() {
			var type = '<?php echo $type; ?>';
			var id = '<?php echo $id; ?>';
			var name = $('[data-eb-subscribe-name]').val();
			var mail = $('[data-eb-subscribe-mail]').val();

			EasyBlog.dialog({
				content: EasyBlog.ajax('site/views/subscription/subscribe', {
					"type": type,
					"id": id,
					"name": name,
					"email": mail
				})
			});
		});
	});
	</script>
<?php } ?>
</div>
