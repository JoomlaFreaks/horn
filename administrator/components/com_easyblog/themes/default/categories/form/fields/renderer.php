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
<?php foreach ($fields as $field) { ?>
<div class="form-group">
	<label for="listing_page_title" class="col-md-5">
		<?php echo JText::_($field->attributes->label); ?>

		<i data-html="true" data-placement="top" data-title="<?php echo JText::_($field->attributes->label); ?>" data-content="<?php echo JText::_($field->attributes->description);?>" 
			data-eb-provide="popover" class="fa fa-question-circle pull-right"></i>
	</label>

	<div class="col-md-7">
		<?php
		// Use text instead of textext
		if ($field->attributes->type == 'textext') {
			$field->attributes->type = 'text';
		}
		?>
		<?php echo $this->output('admin/categories/form/fields/' . $field->attributes->type, array('field' => $field, 'params' => $params)); ?>
	</div>
</div>
<?php } ?>