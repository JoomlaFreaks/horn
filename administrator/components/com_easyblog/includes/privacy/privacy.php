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

class EasyBlogPrivacy
{
	/**
	 * Return a list of privacy options
	 *
	 * @param	none
	 * @return	array	An array of select options
	 **/
	public function getOptions( $type = '', $userId = '' )
	{
		$config		= EB::config();
		$file		= JPATH_ROOT . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_community' . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'core.php';
		$options	= array();

		if ($type == 'teamblog') {
			$options[] = JHTML::_('select.option', '1', JText::_('COM_EASYBLOG_TEAM_MEMBER_ONLY'));
			$options[] = JHTML::_('select.option', '2', JText::_('COM_EASYBLOG_ALL_REGISTERED_USERS'));
			$options[] = JHTML::_('select.option', '3', JText::_('COM_EASYBLOG_EVERYONE'));

			return $options;
		}

		if($type != 'category' )
		{
			if( $config->get( 'main_jomsocial_privacy' ) && JFile::exists( $file ) )
			{
				$options[]	= JHTML::_('select.option', '0', JText::_( 'COM_EASYBLOG_PRIVACY_JOMSOCIAL_ALL' ) );
				$options[]	= JHTML::_('select.option', '20', JText::_( 'COM_EASYBLOG_PRIVACY_JOMSOCIAL_MEMBERS' ) );
				$options[]	= JHTML::_('select.option', '30', JText::_( 'COM_EASYBLOG_PRIVACY_JOMSOCIAL_FRIENDS' ) );
				$options[]	= JHTML::_('select.option', '40', JText::_( 'COM_EASYBLOG_PRIVACY_JOMSOCIAL_ONLY_ME' ) );
			}

			$easysocial = EB::easysocial();
			
			if ($easysocial->exists()) {
				$my			= JFactory::getUser();
				$userId 	= ( $userId ) ? $userId : $my->id;

				$privacyLib = Foundry::privacy( $userId, 'user' );
				$esOptions 	= $privacyLib->getOption( '0', 'blog', $userId, 'easyblog.blog.view' );

				if( isset( $esOptions->option ) && count( $esOptions->option ) > 0 )
				{
					foreach( $esOptions->option as $optionKey => $optionVal )
					{
						$valueInt 	= $privacyLib->toValue( $optionKey );
						$options[]	= JHTML::_('select.option', $valueInt, JText::_( 'COM_EASYBLOG_PRIVACY_EASYSOCIAL_' . strtoupper( $optionKey ) ) );
					}
				}
			}
		}

		// Default values
		if( empty( $options ) )
		{
			$options[]	= JHTML::_('select.option', '0', JText::_( 'COM_EASYBLOG_PRIVACY_VIEWABLE_ALL' ) );
			$options[]	= JHTML::_('select.option', '1', JText::_( 'COM_EASYBLOG_PRIVACY_VIEWABLE_MEMBERS' ) );
			if( $type == 'category' )
			{
			    $options[]	= JHTML::_('select.option', '2', JText::_( 'COM_EASYBLOG_PRIVACY_VIEWABLE_CATEGORY_ACL' ) );
			}
		}

		return $options;
	}

	/**
	 * Check for privacy of the blog post
	 *
	 * @since	5.1
	 * @access	public
	 */
	public function checkPrivacy($blog)
	{
		$obj = new stdClass();
		$obj->allowed = EB::isLoggedIn();
		$obj->message = '';

		// If it is public or site amdin, always allow browser to access.
		if (!$blog->access || EB::isSiteAdmin()) {
			$obj->allowed = true;
			return $obj;
		}

		$my = JFactory::getUser();
		$config = EB::config();

		// EasySocial integrations
		if (EB::easysocial()->exists()) {

			// Post can be seen by anyone logged in
			if ($blog->access == '10') {
				$obj->allowed = EB::isLoggedIn();
				$obj->error = $obj->allowed ? '' : $this->getErrorHTML();
			}

			// Post can only be seen by friends
			if ($blog->access == '30') {
				// if user is the blog author, we always allow.
				$obj->allowed = $my->id == $blog->created_by ? true : false;
				
				if (!$obj->allowed) {
					$obj->allowed = ES::user($my->id ->isFriends($blog->created_by));
				}
				$obj->error = $obj->allowed ? '' : $this->getErrorHTML('privacy.friends');
			}

			// User can only view their own posts
			if ($blog->access == '40') {
				$obj->allowed = $my->id == $blog->created_by;
				$obj->error = $obj->allowed ? '' : $this->getErrorHTML( 'privacy.owner' );
			}

			return $obj;
		}
		
		// Jomsocial integrations
		if ($config->get('main_jomsocial_privacy') && EB::jomsocial()->exists()) {

			if ($blog->access == '20') {
				$obj->allowed = EB::isLoggedIn();
				$obj->error = $obj->allowed ? '' : $this->getErrorHTML();
			}

			if ($blog->access == '30') {
				$obj->allowed = CFactory::getUser($my->id)->isFriendWith($blog->created_by);
				$obj->error = $obj->allowed ? '' : $this->getErrorHTML('privacy.friends');
			}

			if ($blog->access == '40') {
				$obj->allowed = $my->id == $blog->created_by;
				$obj->error = $obj->allowed ? '' : $this->getErrorHTML('privacy.owner');
			}
			
			return $obj;
		}

		if ($blog->access) {
			$obj->allowed = EB::isLoggedIn();
			$obj->error = $obj->allowed ? '' : $this->getErrorHTML();
		}

		// If not integrated with any privacy providers, we assume that the blog
		// is private.
		return $obj;
	}

	public function getErrorHTML( $type = '' )
	{
		switch( $type )
		{
			case 'privacy.friends':
				return JText::_( 'COM_EASYBLOG_PRIVACY_JOMSOCIAL_FRIENDS_ERROR' );
			break;
			case 'privacy.owner':
				return JText::_( 'COM_EASYBLOG_PRIVACY_JOMSOCIAL_NOT_AUTHORIZED_ERROR' );
			break;
			default:

				return JText::_( 'COM_EASYBLOG_PRIVACY_NOT_AUTHORIZED_ERROR' );
			break;
		}
	}
}
