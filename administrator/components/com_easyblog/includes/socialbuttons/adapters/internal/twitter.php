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

class EasyBlogInternalButtonTwitter extends EasyBlogInternalButtons
{
	public $url = 'https://twitter.com/intent/tweet';

	/**
	 * Generates the external link
	 *
	 * @since   5.1
	 * @access  public
	 */
	public function getPermalink()
	{
		// Get the via text
		$via = $this->config->get('main_twitter_button_via_screen_name', '');

		if ($via) {
			$via = JString::substr($via, 1);

			$this->doc->addHeadLink('https://twitter.com/' . $via, 'me');
		}

		$link = $this->url . '?url=' . urlencode($this->permalink);

		if ($via) {
			$link .= '&via=' . $via;
		}
		
		return $link;
	}
}
