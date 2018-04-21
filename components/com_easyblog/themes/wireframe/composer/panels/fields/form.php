<?php
/**
* @package      EasyBlog
* @copyright    Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<?php if ($fields) { ?>
<div class="eb-composer-fieldset" data-eb-composer-panel-fields data-panel-field data-group-id="<?php echo $group->id;?>" data-category-id="<?php echo $id;?>">
	<div class="eb-composer-fieldset-header">
		<strong><?php echo JText::_($group->title);?></strong>
	</div>
	<div class="eb-composer-fieldset-content o-form-horizontal">
		<?php foreach ($fields as $field) { ?>
		<div class="o-form-group">
			<label class="o-control-label">
				<?php if ($field->required) { ?>
				<span class="required">*</span>
				<?php } ?>

				<?php echo $field->getTitle(); ?>
				<i data-html="true" data-placement="bottom" data-title="<?php echo $field->getTitle(); ?>"
					data-content="<?php echo $field->getHelp();?>" data-eb-provide="popover" class="fa fa-question-circle pull-right"></i>
			</label>
			<div class="o-control-input">
				<?php echo $field->getForm($post, 'fields');?>
			</div>
		</div>
		<?php } ?>
	</div>
</div>
<?php } ?>
