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

use RegularLabs\Library\Parameters as RL_Parameters;
use RegularLabs\Library\PluginTag as RL_PluginTag;
use RegularLabs\Library\RegEx as RL_RegEx;

class Params
{
	protected static $params = null;
	protected static $regex  = null;

	public static function get()
	{
		if ( ! is_null(self::$params))
		{
			return self::$params;
		}

		$params = RL_Parameters::getInstance()->getPluginParams('tooltips');

		$params->tag = RL_PluginTag::clean($params->tag);

		self::$params = $params;

		return self::$params;
	}

	public static function getTags($only_start_tags = false)
	{
		$params = self::get();

		list($tag_start, $tag_end) = self::getTagCharacters();

		$tags = [
			[
				$tag_start . $params->tag,
			],
			[
				$tag_start . '/' . $params->tag . $tag_end,
			],
		];

		return $only_start_tags ? $tags[0] : $tags;
	}

	public static function getRegex()
	{
		if ( ! is_null(self::$regex))
		{
			return self::$regex;
		}

		$params = self::get();

		// Tag character start and end
		list($tag_start, $tag_end) = Params::getTagCharacters();
		$tag_start = RL_RegEx::quote($tag_start);
		$tag_end   = RL_RegEx::quote($tag_end);

		$inside_tag = RL_PluginTag::getRegexInsideTag();
		$spaces     = RL_PluginTag::getRegexSpaces();

		self::$regex =
			$tag_start . RL_RegEx::quote($params->tag) . '(?<tip>(?:' . $spaces . '|<)' . $inside_tag . ')' . $tag_end
			. '(?<text>.*?)'
			. $tag_start . '/' . RL_RegEx::quote($params->tag) . $tag_end;

		return self::$regex;
	}

	public static function getTagCharacters()
	{
		$params = self::get();

		if ( ! isset($params->tag_character_start))
		{
			self::setTagCharacters();
		}

		return [$params->tag_character_start, $params->tag_character_end];
	}

	public static function setTagCharacters()
	{
		$params = self::get();

		list(self::$params->tag_character_start, self::$params->tag_character_end) = explode('.', $params->tag_characters);
	}
}
