<?php
/**
 * @package         Tooltips
 * @version         7.2.1
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2018 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace RegularLabs\Plugin\System\Tooltips;

defined('_JEXEC') or die;

use JFactory;
use JHtml;
use RegularLabs\Library\Document as RL_Document;

class Document
{
	public static function addHeadStuff()
	{
		// do not load scripts/styles on feeds or print pages
		if (RL_Document::isFeed() || JFactory::getApplication()->input->getInt('print', 0))
		{
			return;
		}

		$params = Params::get();

		if ( ! $params->load_bootstrap_framework && $params->load_jquery)
		{
			JHtml::_('jquery.framework');
		}

		if ($params->load_bootstrap_framework)
		{
			JHtml::_('bootstrap.framework');
		}


		RL_Document::script('tooltips/script.min.js', ($params->media_versioning ? '7.2.1' : ''));

		if ($params->load_stylesheet)
		{
			RL_Document::style('tooltips/style.min.css', ($params->media_versioning ? '7.2.1' : ''));
		}

		$styles = [];
		if ($params->color_link)
		{
			$styles['.rl_tooltips-link'][] = 'color: ' . $params->color_link;
		}
		if ($params->underline && $params->underline_color)
		{
			$styles['.rl_tooltips-link'][] = 'border-bottom: 1px ' . $params->underline . ' ' . $params->underline_color;
		}
		if ($params->max_width)
		{
			$styles['.rl_tooltips.popover'][] = 'max-width: ' . (int) $params->max_width . 'px';
		}
		if ($params->zindex)
		{
			$styles['.rl_tooltips.popover'][] = 'z-index: ' . (int) $params->zindex;
		}
		if ($params->border_color)
		{
			$styles['.rl_tooltips.popover'][]            = 'border-color: ' . $params->border_color;
			$styles['.rl_tooltips.popover.top .arrow'][] = 'border-top-color: ' . $params->border_color;
		}
		if ($params->bg_color_text)
		{
			$styles['.rl_tooltips.popover'][]                  = 'background-color: ' . $params->bg_color_text;
			$styles['.rl_tooltips.popover.top .arrow:after'][] = 'border-top-color: ' . $params->bg_color_text;
		}
		if ($params->text_color)
		{
			$styles['.rl_tooltips.popover'][] = 'color: ' . $params->text_color;
		}
		if ($params->link_color)
		{
			$styles['.rl_tooltips.popover a'][] = 'color: ' . $params->link_color;
		}
		if ($params->bg_color_title)
		{
			$styles['.rl_tooltips.popover .popover-title'][] = 'background-color: ' . $params->bg_color_title;
		}
		if ($params->title_color)
		{
			$styles['.rl_tooltips.popover .popover-title'][] = 'color: ' . $params->title_color;
		}

		if (empty($styles))
		{
			return;
		}

		$style = [];

		foreach ($styles as $key => $vals)
		{
			$style[] = $key . ' {' . implode(';', $vals) . ';}';
		}

		RL_Document::styleDeclaration(implode(' ', $style), 'Tooltips');
	}

	public static function removeHeadStuff(&$html)
	{
		// Don't remove if tooltips class is found
		if (strpos($html, 'class="rl_tooltips-link') !== false)
		{
			return;
		}

		// remove style and script if no items are found
		RL_Document::removeScriptsStyles($html, 'Tooltips');
		RL_Document::removeScriptsOptions($html, 'Tooltips');
	}
}
