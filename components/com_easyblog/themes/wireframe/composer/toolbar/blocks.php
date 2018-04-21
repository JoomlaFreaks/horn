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
<button type="button" class="btn eb-comp-toolbar__nav-btn" data-toolbar-blocks>
	<i class="fa fa-cube"></i>&nbsp; <?php echo JText::_('COM_EASYBLOG_INSERT_BLOCK');?>
</button>

<div class="dropdown-menu eb-comp-toolbar-dropdown-menu eb-comp-toolbar-dropdown-menu--blocks" data-eb-composer-blocks>
	<div class="eb-comp-toolbar-dropdown-menu__hd">
		<?php echo JText::_('COM_EASYBLOG_INSERT_BLOCK');?>
		<div class="eb-comp-toolbar-dropdown-menu__hd-action">
			<a href="javascript:void(0);" class="eb-comp-toolbar-dropdown-menu__close" data-toolbar-blocks-close>
				<i class="fa fa-times-circle"></i>
			</a>
		</div>
	</div>
	<div class="eb-comp-toolbar-dropdown-menu__bd">

		<div class="eb-comp-toolbar-dropdown-menu__search">
			<input type="text" placeholder="<?php echo JText::_('COM_EASYBLOG_COMPOSER_BLOCKS_SEARCH');?>" data-eb-blocks-search />
		</div>

		<div class="eb-comp-toolbar-blocks-container">

			<?php foreach($blocks as $category => $blockItems) { ?>
				<?php if ((count($blockItems) == 1 && $blockItems[0]->visible == true) || count($blockItems) > 1) { ?>
				<div class="eb-composer-fieldset">
					<div class="eb-composer-fieldset-header">
						<strong><?php echo JText::_('COM_EASYBLOG_BLOCKS_CATEGORY_' . strtoupper($category)); ?></strong>
					</div>
					<div class="eb-composer-fieldset-content">
						<div class="eb-composer-block-menu-group" data-eb-composer-block-menu-group>
							<?php foreach ($blockItems as $block) { ?>
							<div class="eb-composer-block-menu ebd-block<?php echo !$block->visible ? ' is-hidden' : '';?>" data-eb-composer-block-menu data-type="<?php echo $block->type; ?>" data-keywords="<?php echo $block->keywords; ?>">
								<div>
									<i class="<?php echo $block->icon; ?>"></i>
									<span><?php echo $block->title; ?></span>
								</div>
								<textarea data-eb-composer-block-meta data-type="<?php echo $block->type; ?>"><?php echo json_encode($block->meta(), JSON_HEX_QUOT | JSON_HEX_TAG); ?></textarea>
							</div>
							<?php } ?>
						</div>
					</div>
				</div>
				<?php } ?>
			<?php } ?>

			<div class="o-empty">
				<div class="o-empty__content">
					<i class="o-empty__icon fa fa-cube"></i>
					<div class="o-empty__text"><?php echo JText::_('COM_EASYBLOG_COMPOSER_BLOCKS_NOT_FOUND'); ?></div>
				</div>
			</div>

			<div class="o-loader"></div>
		</div>
	</div>
</div>