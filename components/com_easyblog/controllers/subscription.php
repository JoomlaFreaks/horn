<?php
/**
* @package		EasyBlog
* @copyright	Copyright (C) 2010 - 2015 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

require_once(dirname(__FILE__) . '/controller.php');

class EasyBlogControllerSubscription extends EasyBlogController
{
	/**
	 * Allows caller to unsubscribe a person given the id of the subscription
	 *
	 * @since	5.1
	 * @access	public
	 */
	public function unsubscribe()
	{
		// Default redirection url
		$redirect = EBR::_('index.php?option=com_easyblog&view=subscription', false);

		// Default redirection link should link to the front page if the user isn't logged in
		if ($this->my->guest) {
			$redirect = EBR::_('index.php?option=com_easyblog', false);
		}

		$return = $this->input->get('return', '', 'raw');

		if ($return) {

			// Check if the url is base64 encoded, we need to decode it.
			if (base64_encode(base64_decode($return, true)) === $return){
				$return = base64_decode($return);
			}

			$redirect = $return;
		}

		// Get the subscription id
		$data = $this->input->get('data', '', 'raw');
		$subscription = EB::table('Subscriptions');

		// Load up the subscription if id is provided
		if (!$data) {

			// Since this is coming from the front end, we need to check for request forgeries
			EB::checkToken();

			$id = $this->input->get('id', 0, 'int');
			$subscription->load($id);

			// Verify if the user really has access to unsubscribe for guests
			if (!$subscription->id) {
				return JError::raiseError(500, JText::_('COM_EASYBLOG_NOT_ALLOWED_TO_PERFORM_ACTION'));
			}

			// Ensure that the registered user is allowed to unsubscribe.
			if ($subscription->user_id && $this->my->id != $subscription->user_id && !EB::isSiteAdmin()) {
				return JError::raiseError(500, JText::_('COM_EASYBLOG_NOT_ALLOWED_TO_PERFORM_ACTION'));
			}

			// Try to delete the subscription
			$state = $subscription->delete();

		} else {

			// Try to get the email and what not from the query
			$data = base64_decode($data);

			$registry = new JRegistry($data);

			$sid = $registry->get('sid', '');

			// There is possibility that sid contain more tha one id
			$ids = explode(',', $sid);

			// Get the token from the url and ensure that the token matches
			$token = $registry->get('token', '');
			$tokens = explode(',', $token);

			for ($i=0; $i < count($ids); $i++) { 

				$subscription->load($ids[$i]);

				// Verify if the user really has access to unsubscribe for guests
				if (!$subscription->id) {
					return JError::raiseError(500, JText::_('COM_EASYBLOG_NOT_ALLOWED_TO_PERFORM_ACTION'));
				}

				$token = $tokens[$i];

				if ($token != md5($subscription->id . $subscription->created)) {
					JError::raiseError(500, JText::_('COM_EASYBLOG_NOT_ALLOWED_TO_PERFORM_ACTION'));
				}

				// Try to delete the subscription
				$state = $subscription->delete();
			}
			
		}

		// Ensure that the user really owns this item
		if (!$state) {
			$this->info->set($subscription->getError());
			return $this->app->redirect($redirect);
		}

		$this->info->set('COM_EASYBLOG_UNSUBSCRIBED_SUCCESS', 'success');
		return $this->app->redirect($redirect);
	}
}
