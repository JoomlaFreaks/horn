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

require_once(__DIR__ . '/controller.php');

class EasyBlogControllerBloggers extends EasyBlogController
{
	/**
	 * Search within EasyBlog
	 *
	 * @since	5.1
	 * @access	public
	 */
	public function suggest()
	{
		// Check for request forgeries
		EB::checkToken();

		$exclusion = $this->input->get('exclusion', '', 'default');

		$model = EB::model('Bloggers');
		$result = $model->getUsers(true, $exclusion);

		// If there's nothing, just return the empty object.
		if (!$result) {
			return $this->ajax->resolve(array());
		}

		$items = array();

		// Determines if we should use a specific input name
		$inputName = $this->input->get('inputName', '', 'default');

		foreach ($result as $user) {
			$user = EB::user($user);

			$template = EB::template();
			$template->set('user', $user);
			$template->set('inputName', $inputName);

			$items[] = $template->output('site/authors/suggest/item');
		}

		return $this->ajax->resolve($items);
	}
}
