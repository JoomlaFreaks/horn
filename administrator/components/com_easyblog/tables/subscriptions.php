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

require_once(__DIR__ . '/table.php');

class EasyBlogTableSubscriptions extends EasyBlogTable
{
	/**
	 * The id of the subscription
	 * @var int
	 */
	public $id = null;

	/**
	 * uid - object id being subscribed.
	 * @var int
	 */
	public $uid = null;

	/**
	 * utype - subscriptions type
	 * @var string
	 */
	public $utype = null;

	/**
	 * site member id (optional)
	 * @var string
	 */
	public $user_id = null;

	/*
	 * site guest name (optional)
	 * @var string
	 */
	public $fullname = null;

	/*
	 * subscriber email
	 * @var string
	 */
	public $email = null;

	/*
	 * Created datetime of the tag
	 * @var datetime
	 */
	public $created = null;

	/**
	 * Constructor for this class.
	 *
	 * @return
	 * @param object $db
	 */
	public function __construct(&$db)
	{
		parent::__construct( '#__easyblog_subscriptions' , 'id' , $db );
	}

	/**
	 * Override the parents store method so we can send confirmation email
	 *
	 * @since	5.0
	 * @access	public
	 */
	public function store($updateNulls = false)
	{
		$isNew = !$this->id ? true : false;

		// Ensure that the `created` column is valid
		if (!$this->created) {
			$this->created = JFactory::getDate()->toSql();
		}

		// Let the parent save the object first.
	    $state = parent::store($updateNulls);

	    $config = EB::config();
	    
	    // Send confirmation email to subscribers
	    if ($isNew && $config->get('main_subscription_confirmation')) {

	    	$subscription = EB::subscription();
	    	$template = $subscription->getTemplate();

			$template->uid = $this->uid;
			$template->utype = $this->utype;
			$template->user_id = $this->user_id;
			$template->uemail = $this->email;
			$template->ufullname = $this->fullname;
			$template->ucreated = $this->created;

			$target = $this->getObject();

			$template->targetname = $target->title;
			$template->targetlink = $target->objPermalink;

			$subscription->addMailQueue($template);
	    }

		// Notify site admins when there is a new subscriber
		if ($isNew && $config->get('main_subscription_admin_notification')) {
			$this->notifyAdmin();
		}

		// Notify site admins when there is a new subscriber
		if ($isNew && $this->utype == EBLOG_SUBSCRIPTION_BLOGGER && $config->get('main_subscription_author_notification')) {
			$this->notifyAuthor();
		}

		return $state;
	}

	/**
	 * Notifies author when a new user subscribes to their blog
	 *
	 * @since	5.1.9
	 * @access	public
	 */
	public function notifyAuthor()
	{
		$data = array(
						'subscriber' => $this->fullname,
						'subscriberDate' => EB::date()->format(JText::_('DATE_FORMAT_LC1')),
						'type' => $this->utype
					);

		$data['itemName'] = '';
		$data['heading'] = JText::_('COM_EASYBLOG_MAIL_TEMPLATE_NEW_SUBSCRIBER_HEADING_SITE');
		$data['heading'] = JText::_('COM_EASYBLOG_MAIL_TEMPLATE_NEW_SUBSCRIBER_HEADING_FOR_AUTHOR');

		$author = EB::user($this->uid);

		$obj = new StdClass();
		$obj->unsubscribe = false;
		$obj->email = $author->user->email;

		$emails = array($obj);

		$lib = EB::notification();
		$lib->send($emails, $data['heading'], 'subscription.notification', $data);	
	}

	/**
	 * Notifies site admin when a new user subscribes
	 *
	 * @since	5.0
	 * @access	public
	 */
	public function notifyAdmin()
	{
		$data = array(
						'subscriber' => $this->fullname,
						'subscriberDate' => EB::date()->format(JText::_('DATE_FORMAT_LC1')),
						'type' => $this->utype
					);

		$data['itemName'] = '';
		$data['heading'] = JText::sprintf('COM_EASYBLOG_MAIL_TEMPLATE_NEW_SUBSCRIBER_HEADING_SITE');

		if ($this->utype == 'entry') {
			$post = EB::post($this->uid);

			$data['itemName'] = $post->title;
		}

		if ($this->utype == 'category') {
			$category = EB::table('Category');
			$category->load($this->uid);

			$data['itemName'] = $category->title;
			$data['heading'] = JText::sprintf('COM_EASYBLOG_MAIL_TEMPLATE_NEW_SUBSCRIBER_HEADING_CATEGORY', $category->title);
		}

		if ($this->utype == 'blogger') {
			$blogger = EB::user($this->uid);

			$data['itemName'] = $blogger->getName();
			$data['heading'] = JText::sprintf('COM_EASYBLOG_MAIL_TEMPLATE_NEW_SUBSCRIBER_HEADING_BLOGGER', $blogger->getName());
		}

		$title = JText::_('COM_EASYBLOG_SUBSCRIPTION_NEW_' . strtoupper($this->utype));
		$emails = array();

		$lib = EB::notification();

		// If custom email addresses is specified, use that instead
		$config = EB::config();
		
		if ($config->get('custom_email_as_admin')) {
			$lib->getCustomEmails($emails);
		} else {
			$lib->getAdminEmails($emails);
		}

		$lib->send($emails, $title, 'subscription.notification', $data);
	}

	/**
	 * Retrieves the object of the subscription item
	 *
	 * @since	5.1
	 * @access	public
	 */
	public function getObject()
	{
		if ($this->utype == 'category') {
			$obj = EB::table('Category');
			$obj->load($this->uid);
			$obj->objAvatar = $obj->getAvatar();
			$obj->objPermalink = $obj->getExternalPermalink();
		}

		if ($this->utype == 'blogger') {
			$obj = EB::user($this->uid);
			$obj->title = $obj->getName();
			$obj->objAvatar = $obj->getAvatar();
			$obj->objPermalink = $obj->getExternalPermalink();
		}

		if ($this->utype == 'site') {
			$obj = new stdClass();
			$obj->title = EB::config()->get('main_title');
			$obj->permalink = EBR::getRoutedURL('index.php?option=com_easyblog', false, true);
			$obj->objAvatar = '';
			$obj->objPermalink = $obj->permalink;
		}

		if ($this->utype == 'teamblog' || $this->utype == 'team') {

			$team = EB::table('Teamblog');
			$team->load($this->uid);

			$obj = new stdClass();
			$obj->title = $team->title;
			$obj->objAvatar = $team->getAvatar();
			$obj->objPermalink = $team->getExternalPermalink();
		}

		if ($this->utype == 'entry') {

			// Get the post object
			$post = EB::post($this->uid);

			$obj = new stdClass();
			$obj->title = $post->title;
			$obj->objPermalink = $post->getExternalPermalink();
			$obj->objAvatar = $post->getAuthor()->getAvatar();
		}
		
		return $obj;
	}

	/**
	 * Retrieves the date object for the creation date of this subscription
	 *
	 * @since	5.0
	 * @access	public
	 */
	public function getSubscriptionDate()
	{
		$date = EB::date($this->created);

		return $date;
	}

	/**
	 * Determine this current email whether already subscribed 
	 *
	 * @since	5.1
	 * @access	public
	 */
	public function isSubscribed($email, $uid, $type)
	{
		$model = EB::model('subscription');

		$result = $model->subscriptionExist($email, $uid, $type);

		return $result;
	}	
}
