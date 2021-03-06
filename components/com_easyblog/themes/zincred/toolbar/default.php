<?php
/**
* @package		EasyBlog
* @copyright	Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<?php echo EB::renderModule('easyblog-before-toolbar'); ?>

<div class="eb-header">
	<?php if ($heading || $this->config->get('layout_headers')) { ?>
	<div class="eb-brand">
		<?php if ($view == 'entry') { ?>
		<h2 class="eb-brand-name reset-heading"><?php echo JText::_($title);?></h2>
		<?php } ?>

		<?php if ($view != 'entry') { ?>
		<h1 class="eb-brand-name reset-heading"><?php echo JText::_($title);?></h1>
		<?php } ?>

		<?php if ($this->config->get('layout_header_description')) { ?>
			<div class="eb-brand-bio"><?php echo JText::_($desc);?></div>
		<?php } ?>
	</div>
	<?php } ?>

	<?php if ($this->config->get('layout_toolbar')) { ?>
	<div role="navigation" class="eb-navbar fd-cf <?php echo $this->isMobile() ? 'is-mobile' : '';?>">
		<ul class="eb-navbar-nav eb-navbar-left reset-list float-list">
			<li class="dropdown_">
				<a href="#" class="dropdown-toggle_" data-bp-toggle="dropdown">
					<i class="fa fa-bars"></i>
				</a>

				<ul class="eb-navbar-dropdown dropdown-menu dropdown-menu-left" role="menu">
					<?php if ($this->config->get('layout_latest')) { ?>
					<li class="<?php echo ($views->latest) ? 'active' : ''; ?>">
						<a href="<?php echo EB::_('index.php?option=com_easyblog');?>">
							<i class="fa fa-home"></i>
							<?php echo JText::_('COM_EASYBLOG_TOOLBAR_LATEST_POSTS');?>
						</a>
					</li>
					<?php } ?>

					<?php if ($this->config->get('layout_categories')) { ?>
					<li class="<?php echo $views->categories ? 'active' : '';?>">
						<a href="<?php echo EB::_('index.php?option=com_easyblog&view=categories');?>">
							<i class="fa fa-folder-open"></i>
							<?php echo JText::_('COM_EASYBLOG_TOOLBAR_CATEGORIES');?>
						</a>
					</li>
					<?php } ?>

					<?php if ($this->config->get('layout_tags')) { ?>
					<li class="<?php echo $views->tags ?  'active' : ''; ?>">
						<a href="<?php echo EB::_('index.php?option=com_easyblog&view=tags');?>">
							<i class="fa fa-tags"></i>
							<?php echo JText::_('COM_EASYBLOG_TOOLBAR_TAGS');?>
						</a>
					</li>
					<?php } ?>

					<?php if ($this->config->get('layout_bloggers') && !$bloggerMode) { ?>
					<li class="<?php echo $views->blogger ?  'active' : ''; ?>">
						<a href="<?php echo EB::_('index.php?option=com_easyblog&view=blogger');?>">
							<i class="fa fa-user"></i>
							<?php echo JText::_('COM_EASYBLOG_TOOLBAR_BLOGGERS');?></span>
						</a>
					</li>
					<?php } ?>

					<?php if ($this->config->get('layout_teamblog') && !$bloggerMode) { ?>
					<li class="<?php echo $views->teamblog ?  'active' : ''; ?>">
						<a href="<?php echo EB::_('index.php?option=com_easyblog&view=teamblog');?>">
							<i class="fa fa-group"></i>
							<?php echo JText::_('COM_EASYBLOG_TOOLBAR_TEAMBLOGS');?></span>
						</a>
					</li>
					<?php } ?>

					<?php if ($this->config->get('layout_archives')) { ?>
					<li class="<?php echo $views->archive ?  'active' : ''; ?>">
						<a href="<?php echo EB::_('index.php?option=com_easyblog&view=archive');?>">
							<i class="fa fa-archive"></i>
							<?php echo JText::_('COM_EASYBLOG_TOOLBAR_ARCHIVES');?></span>
						</a>
					</li>
					<?php } ?>

					<?php if ($this->config->get('layout_calendar')) { ?>
					<li class="<?php echo $views->calendar ?  'active' : ''; ?>">
						<a href="<?php echo EB::_('index.php?option=com_easyblog&view=calendar');?>">
							<i class="fa fa-calendar"></i>
							<?php echo JText::_('COM_EASYBLOG_TOOLBAR_CALENDAR');?></span>
						</a>
					</li>
					<?php } ?>
				</ul>
			</li>
		</ul>

		<ul class="eb-navbar-nav eb-navbar-right reset-list float-list">
			<li>
				<a href="javascript:void(0);" data-eb-toolbar-search-toggle
					data-original-title="<?php echo JText::_('COM_EASYBLOG_SEARCH');?>"
					data-placement="bottom"
					data-eb-provide="tooltip"
					>
					<i class="fa fa-search"></i>
				</a>
			</li>

			<?php if ($this->config->get('main_sitesubscription') && $this->acl->get('allow_subscription') && !$subscription->id) { ?>
				<li>
					<a class="eb-brand-email" href="javascript:void(0);"
						data-blog-subscribe
						data-type="site"
						data-original-title="<?php echo JText::_('COM_EASYBLOG_SUBSCRIPTION_SUBSCRIBE_TO_SITE');?>"
						data-placement="bottom"
						data-eb-provide="tooltip"
					>
						<i class="fa fa-envelope"></i>
						<span class="eb-text"><?php echo JText::_('COM_EASYBLOG_SUBSCRIPTION_SUBSCRIBE_TO_SITE');?></span>
					</a>
				</li>
			<?php } elseif (!$this->my->guest && $subscription->id) { ?>
				<li>
					<a class="eb-brand-email" href="javascript:void(0);"
						data-blog-unsubscribe
						data-subscription-id="<?php echo $subscription->id;?>"
						data-return="<?php echo base64_encode(JRequest::getUri());?>"
						data-original-title="<?php echo JText::_('COM_EASYBLOG_SUBSCRIPTION_UNSUBSCRIBE_TO_SITE');?>"
						data-placement="bottom"
						data-eb-provide="tooltip"
					>
						<i class="fa fa-envelope"></i>
						<span class="eb-text"><?php echo JText::_('COM_EASYBLOG_SUBSCRIPTION_UNSUBSCRIBE_TO_SITE');?></span>
					</a>
				</li>
			<?php } ?>

			<?php if ($this->config->get('main_rss') && $this->acl->get('allow_subscription_rss')) { ?>
				<li>
					<a class="eb-brand-rss" href="<?php echo EB::feeds()->getFeedURL('index.php?option=com_easyblog');?>"
						data-original-title="<?php echo JText::_('COM_EASYBLOG_SUBSCRIBE_FEEDS');?>"
						data-placement="bottom"
						data-eb-provide="tooltip"
					>
						<i class="fa fa-rss-square"></i>
						<span class="eb-text"><?php echo JText::_('COM_EASYBLOG_SUBSCRIBE_FEEDS');?></span>
					</a>
				</li>
			<?php } ?>

			<?php if (($this->acl->get('publish_entry') ) || ($this->acl->get('add_entry') ) || ($this->acl->get('delete_entry') )) { ?>
			<li class="hidden-mobile">
				<a href="<?php echo EB::_('index.php?option=com_easyblog&view=dashboard&layout=quickpost');?>"
					data-original-title="<?php echo JText::_('COM_EASYBLOG_TOOLBAR_QUICK_POST');?>"
					data-placement="bottom"
					data-eb-provide="tooltip"
					>
					<i class="fa fa-bolt"></i>
				</a>
			</li>
			<?php } ?>

			<?php if (($this->acl->get('publish_entry') ) || ($this->acl->get('add_entry') ) || ($this->acl->get('delete_entry') )) { ?>
			<li class="hidden-mobile">
				<a href="<?php echo EB::composer()->getComposeUrl(); ?>" data-original-title="<?php echo JText::_('COM_EASYBLOG_TOOLBAR_NEW_POST_TIPS');?>" data-placement="bottom" data-eb-provide="tooltip">
					<i class="fa fa-pencil"></i>
				</a>
			</li>
			<?php } ?>

			<?php if ($this->config->get('layout_option_toolbar') && !$this->my->guest) { ?>
			<li class="dropdown_">
				<a href="#" class="dropdown-toggle_" data-bp-toggle="dropdown">
					<i class="fa fa-cog"></i>
				 </a>
				 <ul class="eb-navbar-dropdown dropdown-menu dropdown-menu-right" role="menu">
					<?php if (($this->acl->get('publish_entry') ) || ($this->acl->get('add_entry') ) || ($this->acl->get('delete_entry') )) { ?>
					<li class="display-mobile">
						<a href="<?php echo EB::_('index.php?option=com_easyblog&view=dashboard&layout=quickpost');?>"
							data-original-title="<?php echo JText::_('COM_EASYBLOG_TOOLBAR_QUICK_POST');?>"
							data-placement="bottom"
							data-eb-provide="tooltip"
							>
							<i class="fa fa-bolt"></i>
							<?php echo JText::_('COM_EASYBLOG_TOOLBAR_QUICK_POST');?>
						</a>
					</li>
					<?php } ?>

					<?php if (($this->acl->get('publish_entry') ) || ($this->acl->get('add_entry') ) || ($this->acl->get('delete_entry') )) { ?>
					<li class="display-mobile">
						<a href="<?php echo EB::composer()->getComposeUrl(); ?>"
							data-original-title="<?php echo JText::_('COM_EASYBLOG_TOOLBAR_NEW_POST_TIPS');?>"
							data-placement="bottom"
							data-eb-provide="tooltip"
							target="_blank"
							>
								<i class="fa fa-pencil"></i>
								<?php echo JText::_('COM_EASYBLOG_TOOLBAR_NEW_POST');?>
						</a>
					</li>
					<?php } ?>

					<?php if (($this->acl->get('publish_entry') ) || ($this->acl->get('add_entry') ) || ($this->acl->get('delete_entry') )) { ?>
					<li class="display-mobile divider"></li>
					<?php } ?>

					<?php if ($this->config->get('toolbar_editprofile')){ ?>
					<li>
						<a href="<?php echo EB::_('index.php?option=com_easyblog&view=dashboard&layout=profile');?>">
							<i class="fa fa-cog"></i>
							<?php echo JText::_('COM_EASYBLOG_TOOLBAR_EDIT_PROFILE');?>
						</a>
					</li>
					<?php } ?>

					<?php if (($this->acl->get('publish_entry') ) || ($this->acl->get('add_entry') ) || ($this->acl->get('delete_entry') )) { ?>
					<li>
						<a href="<?php echo EB::_('index.php?option=com_easyblog&view=dashboard&layout=entries');?>">
							<i class="fa fa-file-text-o"></i>
							<?php echo JText::_('COM_EASYBLOG_TOOLBAR_MANAGE_POSTS');?>
						</a>
					</li>
					<?php } ?>

					<?php if (($this->acl->get('add_entry') )) { ?>
					<li>
						<a href="<?php echo EB::_('index.php?option=com_easyblog&view=dashboard&layout=edit');?>">
							<i class="fa fa-ticket"></i>
							<?php echo JText::_('COM_EASYBLOG_TOOLBAR_MANAGE_PENDING');?>
						</a>
					</li>
					<?php } ?>

					<?php if ($this->acl->get('manage_comment') && EB::comment()->isBuiltin()) { ?>
					<li>
						<a href="<?php echo EB::_('index.php?option=com_easyblog&view=dashboard&layout=comments');?>">
							<i class="fa fa-comments"></i>
							<?php echo JText::_('COM_EASYBLOG_TOOLBAR_MANAGE_COMMENTS');?>
						</a>
					</li>
					<?php } ?>

					<?php if ($this->acl->get('create_category') ) { ?>
					<li>
						<a href="<?php echo EB::_('index.php?option=com_easyblog&view=dashboard&layout=categories');?>">
							<i class="fa fa-folder-o"></i>
							<?php echo JText::_('COM_EASYBLOG_TOOLBAR_CATEGORIES');?>
						</a>
					</li>
					<?php } ?>

					<?php if ($this->acl->get('create_tag') ) { ?>
					<li>
						<a href="<?php echo EB::_('index.php?option=com_easyblog&view=dashboard&layout=tags');?>">
							<i class="fa fa-tags"></i>
							<?php echo JText::_('COM_EASYBLOG_TOOLBAR_TAGS');?>
						</a>
					</li>
					<?php } ?>

					<?php if ($this->acl->get('allow_subscription')) { ?>
					<li>
						<a href="<?php echo EB::_('index.php?option=com_easyblog&view=subscription');?>">
							<i class="fa fa-envelope"></i> <?php echo JText::_('COM_EASYBLOG_TOOLBAR_MANAGE_SUBSCRIPTIONS');?>
						</a>
					</li>
					<?php } ?>

					<?php if ((EB::isTeamAdmin() || EB::isSiteAdmin()) && $this->config->get('toolbar_teamrequest')){ ?>
					<li>
						<a href="<?php echo EB::_('index.php?option=com_easyblog&view=dashboard&layout=edit');?>">
							<i class="fa fa-users"></i>
							<?php echo JText::_('COM_EASYBLOG_TOOLBAR_TEAM_REQUESTS');?>
						</a>
					</li>
					<?php } ?>

					<?php if ($this->config->get('toolbar_logout')){ ?>
					<li class="divider"></li>
					<li>
						<a href="javascript:void(0);" data-blog-toolbar-logout>
							<i class="fa fa-power-off"></i>
							<?php echo JText::_('COM_EASYBLOG_TOOLBAR_SIGN_OUT');?>
						</a>
					</li>
					<?php } ?>
				</ul>
			</li>
			<?php } ?>

			<?php if ($this->config->get('layout_login') && $this->my->guest) { ?>
			<li class="dropdown_">
				<a href="javascript:void(0);" class="dropdown-toggle_"
					data-original-title="<?php echo JText::_('COM_EASYBLOG_TOOLBAR_LOGIN_TIPS');?>"
					data-placement="bottom"
					data-eb-provide="tooltip"
					data-bp-toggle="dropdown"					
				>
					<i class="fa fa-lock"></i>
					<span class="eb-text"><?php echo JText::_('COM_EASYBLOG_TOOLBAR_LOGIN');?></span>
				</a>

				<div class="eb-navbar-dropdown dropdown-menu dropdown-menu-right">
					 <form class="eb-navbar-login" action="<?php echo JRoute::_( 'index.php' );?>" method="post">
						<div class="form-group">
							<label>
								<span><?php echo JText::_('COM_EASYBLOG_USERNAME') ?></span>
								<?php if (EB::isRegistrationEnabled()) { ?>
								<a href="<?php echo EB::getRegistrationLink();?>" class="float-r"><?php echo JText::_( 'COM_EASYBLOG_REGISTER' );?></a>
								<?php } ?>
							</label>
							<input id="username" type="text" name="username" class="form-control" alt="username" tabindex="31"/>
						</div>
						<div class="form-group">
							<label>
								<span class="trait"><?php echo JText::_('COM_EASYBLOG_PASSWORD') ?></span>
								<a href="<?php echo EB::getResetPasswordLink();?>" class="float-r"><?php echo JText::_( 'COM_EASYBLOG_FORGOTTEN_PASSWORD' );?></a>
							</label>
							<input type="password" id="passwd" class="form-control" name="password" tabindex="32"/>
						</div>
						<div class="form-action">
							<?php if(JPluginHelper::isEnabled('system', 'remember')) { ?>
								<div class="checkbox">
									<input id="remember-me" type="checkbox" name="remember" value="1" class="rip" tabindex="33"/>
									<label for="remember-me">
										<?php echo JText::_('COM_EASYBLOG_REMEMBER_ME') ?>
									</label>
								</div>
							<?php } ?>
							<button class="btn btn-primary btn-block" type="submit" tabindex="34"><?php echo JText::_('COM_EASYBLOG_LOGIN') ?></button>
						</div>
						<input type="hidden" value="com_users"  name="option">
						<input type="hidden" value="user.login" name="task">
						<input type="hidden" name="return" value="<?php echo $return; ?>" />
						<?php echo $this->html('form.token'); ?>

						<?php if ($this->config->get('integrations_jfbconnect_login')) { ?>
							<?php echo EB::jfbconnect()->getButtons();?>
						<?php } ?>
					</form>
				</div>
			</li>
			<?php } ?>
		</ul>

		<form action="<?php echo JRoute::_('index.php');?>" method="post" data-blog-logout-form class="hide">
			<input type="hidden" value="com_users"  name="option" />
			<input type="hidden" value="user.logout" name="task" />
			<input type="hidden" value="<?php echo $return; ?>" name="return" />
			<?php echo $this->html('form.token'); ?>
		</form>

		<?php if ($this->config->get('layout_search')) { ?>
		<form class="nav-search eb-navbar-search hidden" method="post" action="<?php echo JRoute::_('index.php');?>" data-eb-toolbar-search-form>
			<i class="fa fa-search"></i>
			<div>
				<input type="text" name="query" class="form-control" placeholder="<?php echo JText::_('COM_EASYBLOG_TOOLBAR_PLACEHOLDER_SEARCH');?>" />
				<?php echo $this->html('form.action', 'search.query');?>
			</div>
			<i class="fa fa-close" data-eb-toolbar-search-toggle></i>
		</form>
		<?php } ?>
	</div>
	<?php } ?>
</div>

<script type="text/javascript">EasyBlog.ready(function($){$(document).on("click","[data-eb-toolbar-search-toggle]",function(){$("[data-eb-toolbar-search-form]").toggleClass("hidden");});});</script>

<?php echo EB::renderModule('easyblog-after-toolbar'); ?>
