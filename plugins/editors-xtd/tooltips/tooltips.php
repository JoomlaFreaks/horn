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

defined('_JEXEC') or die;

if ( ! is_file(JPATH_LIBRARIES . '/regularlabs/autoload.php'))
{
	return;
}

require_once JPATH_LIBRARIES . '/regularlabs/autoload.php';

/**
 * Button Plugin that places Editor Buttons
 */
class PlgButtonTooltips
	extends \RegularLabs\Library\EditorButton
{
	var $require_core_auth = false;
}
