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
<?php echo EB::renderModule('easyblog-before-toolbar'); ?>

<?php if ($heading || $this->config->get('layout_headers') || ($this->config->get('layout_toolbar') && $this->acl->get('access_toolbar'))) { ?>
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

	<?php if ($this->config->get('layout_toolbar') && $this->acl->get('access_toolbar')) { ?>
	<div class="eb-navbar <?php echo $showFooter ? 'has-footer' : '';?>" data-es-toolbar="">
		<div class="eb-navbar__body">
			<a class="eb-navbar__footer-toggle" href="javascript:void(0);" data-eb-toolbar-toggle>
				<i class="fa fa-bars"></i>
			</a>

			<div class="hide" data-eb-mobile-toolbar>
				<div class="o-row">
					<ol class="g-list-inline eb-navbar__footer-submenu">
						<li class="<?php echo $view == 'latest' ? 'is-active' : '' ?>">
							<a href="<?php echo EBR::_('index.php?option=com_easyblog');?>" class="eb-navbar__footer-link">
								<i class="fa fa-home"></i>
								<span><?php echo JText::_('COM_EASYBLOG_TOOLBAR_LATEST_POSTS');?></span>
							</a>
						</li>
						<?php if ($this->config->get('layout_categories')) { ?>
						<li class="<?php echo $view == 'categories' ? 'is-active' : '' ?>">
							<a href="<?php echo EBR::_('index.php?option=com_easyblog&view=categories');?>" class="eb-navbar__footer-link">
							<i class="fa fa-folder-open"></i>
							<span><?php echo JText::_('COM_EASYBLOG_TOOLBAR_CATEGORIES');?></span>
							</a>
						</li>
						<?php } ?>

						<?php if ($this->config->get('layout_tags')) { ?>
						<li class="<?php echo $view == 'tags' ? 'is-active' : '' ?>">
							<a href="<?php echo EBR::_('index.php?option=com_easyblog&view=tags');?>" class="eb-navbar__footer-link">
								<i class="fa fa-tags"></i>
								<span><?php echo JText::_('COM_EASYBLOG_TOOLBAR_TAGS');?></span>
							</a>
						</li>
						<?php } ?>

						<?php if ($this->config->get('layout_bloggers') && !$bloggerMode) { ?>
						<li class="<?php echo $view == 'blogger' ? 'is-active' : '' ?>">
							<a href="<?php echo EBR::_('index.php?option=com_easyblog&view=blogger');?>" class="eb-navbar__footer-link">
								<i class="fa fa-user"></i>
								<span><?php echo JText::_('COM_EASYBLOG_TOOLBAR_BLOGGERS');?></span>
							</a>
						</li>
						<?php } ?>

						<?php if ($this->config->get('layout_teamblog')) { ?>
						<li class="<?php echo $view == 'teamblog' ? 'is-active' : '' ?>">
							<a href="<?php echo EBR::_('index.php?option=com_easyblog&view=teamblog');?>" class="eb-navbar__footer-link">
								<i class="fa fa-group"></i>
								<span><?php echo JText::_('COM_EASYBLOG_TOOLBAR_TEAMBLOGS');?></span>
							</a>
						</li>
						<?php } ?>

						<?php if ($this->config->get('layout_archives')) { ?>
						<li class="<?php echo $view == 'archive' ? 'is-active' : '' ?>">
							<a href="<?php echo EBR::_('index.php?option=com_easyblog&view=archive');?>" class="eb-navbar__footer-link">
								<i class="fa fa-archive"></i>
								<span><?php echo JText::_('COM_EASYBLOG_TOOLBAR_ARCHIVES');?></span>
							</a>
						</li>
						<?php } ?>

						<?php if ($this->config->get('layout_calendar')) { ?>
						<li class="<?php echo $view == 'calendar' ? 'is-active' : '' ?>">
							<a href="<?php echo EBR::_('index.php?option=com_easyblog&view=calendar');?>" class="eb-navbar__footer-link">
								<i class="fa fa-calendar"></i>
								<span><?php echo JText::_('COM_EASYBLOG_TOOLBAR_CALENDAR');?></span>
							</a>
						</li>
						<?php } ?>
						
						<?php if ($this->config->get('main_sitesubscription') && $this->acl->get('allow_subscription')) { ?>
						<li class="<?php echo $subscription->id ? 'hide' : ''; ?>"
							data-blog-subscribe
							data-type="site"
							data-original-title="<?php echo JText::_('COM_EASYBLOG_TOOLBAR_MOBILE_SUBSCRIBE');?>"
							data-placement="top"
							data-eb-provide="tooltip"
						>
							<a href="javascript:void(0);" class="eb-navbar__footer-link">
								<i class="fa fa-envelope"></i>
								<span><?php echo JText::_('COM_EASYBLOG_TOOLBAR_MOBILE_SUBSCRIBE');?></span>
							</a>
						</li>
						<li class="<?php echo $subscription->id ? '' : 'hide'; ?>" 
							data-blog-unsubscribe
							data-subscription-id="<?php echo $subscription->id;?>"
							data-return="<?php echo base64_encode(JRequest::getUri());?>"
							data-original-title="<?php echo JText::_('COM_EASYBLOG_TOOLBAR_MOBILE_UNSUBSCRIBE');?>"
							data-placement="top"
							data-eb-provide="tooltip"
						>
							<a href="javascript:void(0);" class="eb-navbar__footer-link">
								<i class="fa fa-envelope"></i>
								<span><?php echo JText::_('COM_EASYBLOG_TOOLBAR_MOBILE_UNSUBSCRIBE');?></span>
							</a>
						</li>
						<?php } ?>
						<li class="">
							<a href="<?php echo EB::feeds()->getFeedURL('index.php?option=com_easyblog');?>" class="eb-navbar__footer-link">
								<i class="fa fa-rss-square"></i>
								<span><?php echo JText::_('COM_EASYBLOG_TOOLBAR_MOBILE_FEEDS');?></span>
							</a>
						</li>
					</ol>
				</div>
			</div>

			<nav class="o-nav eb-navbar__o-nav">

				<?php if ($this->acl->get('add_entry')) { ?>
				<div class="o-nav__item">
					<a class="o-nav__link eb-navbar__icon-link" href="<?php echo EB::composer()->getComposeUrl(); ?>"
						data-eb-provide="tooltip" data-placement="top" data-original-title="<?php echo JText::_('COM_EASYBLOG_TOOLBAR_NEW_POST_TIPS');?>"
					>
						<i class="fa fa-pencil"></i>
						<span class="eb-navbar__link-text"><?php echo JText::_('COM_EASYBLOG_TOOLBAR_NEW_POST');?></span>
					</a>
				</div>
				<?php } ?>

				<?php if ($this->acl->get('add_entry') && $this->config->get('main_microblog')) { ?>
				<div class="o-nav__item">
					<a class="o-nav__link eb-navbar__icon-link" href="<?php echo EB::_('index.php?option=com_easyblog&view=dashboard&layout=quickpost'); ?>"
						data-original-title="<?php echo JText::_('COM_EASYBLOG_TOOLBAR_QUICK_POST_TIPS');?>"
						data-placement="top"
						data-eb-provide="tooltip"
					>
						<i class="fa fa-bolt"></i>
						<span class="eb-navbar__link-text"><?php echo JText::_('COM_EASYBLOG_TOOLBAR_QUICK_POST');?></span>
					</a>
				</div>
				<?php } ?>


				<?php if ($this->config->get('main_sitesubscription') && $this->acl->get('allow_subscription') && !$this->isMobile()) { ?>
					<div class="o-nav__item <?php echo $subscription->id ? 'hide' : ''; ?>"
						data-blog-subscribe
						data-type="site"
						data-original-title="<?php echo JText::_('COM_EASYBLOG_SUBSCRIPTION_SUBSCRIBE_TO_SITE');?>"
						data-placement="top"
						data-eb-provide="tooltip"
					>
						<a class="o-nav__link eb-navbar__icon-link" href="javascript:void(0);">
							<i class="fa fa-envelope"></i>
							<span class="eb-navbar__link-text"><?php echo JText::_('COM_EASYBLOG_SUBSCRIPTION_SUBSCRIBE_TO_SITE');?></span>
						</a>
					</div>
					<div class="o-nav__item <?php echo $subscription->id ? '' : 'hide'; ?>"
						data-blog-unsubscribe
						data-subscription-id="<?php echo $subscription->id;?>"
						data-return="<?php echo base64_encode(JRequest::getUri());?>"
						data-original-title="<?php echo JText::_('COM_EASYBLOG_SUBSCRIPTION_UNSUBSCRIBE_TO_SITE');?>"
						data-placement="top"
						data-eb-provide="tooltip"
					>
						<a class="o-nav__link eb-navbar__icon-link" href="javascript:void(0);">
							<i class="fa fa-envelope"></i>
							<span class="eb-navbar__link-text"><?php echo JText::_('COM_EASYBLOG_SUBSCRIPTION_UNSUBSCRIBE_TO_SITE');?></span>
						</a>
					</div>
				<?php } ?>


				<?php if ($this->config->get('main_rss') && $this->acl->get('allow_subscription_rss') && !$this->isMobile()) { ?>
				<div class="o-nav__item">
					<a class="o-nav__link eb-navbar__icon-link" href="<?php echo EB::feeds()->getFeedURL('index.php?option=com_easyblog');?>"
						data-original-title="<?php echo JText::_('COM_EASYBLOG_SUBSCRIBE_FEEDS');?>"
						data-placement="top"
						data-eb-provide="tooltip"
					>
						<i class="fa fa-rss-square eb-navbar-rss-icon"></i>
						<span class="eb-navbar__link-text"><?php echo JText::_('COM_EASYBLOG_SUBSCRIBE_FEEDS');?></span>
					</a>
				</div>
				<?php } ?>

				<?php if ($this->isMobile()) { ?>
				<div class="o-nav__item">
					<a href="javascript:void(0);" class="o-nav__link eb-navbar__icon-link" data-eb-mobile-toolbar-search>
						<i class="fa fa-search"></i>
					</a>
				</div>
				<?php } ?>

				<?php if ($this->config->get('layout_login') && $this->my->guest) { ?>
				<div class="o-nav__item dropdown_">
					<a href="javascript:void(0);" class="o-nav__link eb-navbar__icon-link dropdown-toggle_" data-bp-toggle="dropdown" role="button" aria-expanded="false">
						<i class="fa fa-lock"></i>
						<span class="eb-navbar__link-text"><?php echo JText::_('COM_EASYBLOG_TOOLBAR_SETTINGS');?></span>
					</a>
					 <div id="eb-navbar-signin" role="menu" class="eb-navbar__dropdown-menu eb-navbar__dropdown-menu--signin dropdown-menu bottom-right">
						<div class="eb-arrow"></div>
						<div class="popbox-dropdown">
							<div class="popbox-dropdown__hd">
								<div class="o-flag o-flag--rev">
									<div class="o-flag__body">
										<div class="popbox-dropdown__title">Sign In</div>
										<?php if (EB::isRegistrationEnabled()) { ?>
										<div class="popbox-dropdown__meta">
											If you are new here, <a href="<?php echo EB::getRegistrationLink();?>"><?php echo JText::_( 'COM_EASYBLOG_REGISTER' );?></a> </div>
										<?php } ?>
									</div>
								</div>
							</div>
							<div class="popbox-dropdown__bd">
								<form class="popbox-dropdown-signin" action="<?php echo JRoute::_( 'index.php' );?>" method="post">
									<div class="form-group">
										<input name="username" autocomplete="off" class="form-control" id="es-username" placeholder="<?php echo JText::_('COM_EASYBLOG_USERNAME') ?>" type="text" tabindex="31">
									</div>
									<div class="form-group">
										<input name="password" class="form-control" id="passwd" autocomplete="off" placeholder="<?php echo JText::_('COM_EASYBLOG_PASSWORD') ?>" type="password" tabindex="32">
									</div>
									<div class="popbox-dropdown-signin__action">
										<div class="popbox-dropdown-signin__action-col">
											<?php if(JPluginHelper::isEnabled('system', 'remember')) { ?>
												<div class="eb-checkbox">
													<input id="remember-me" type="checkbox" name="remember" value="1" class="rip" tabindex="33"/>
													<label for="remember-me">
														<?php echo JText::_('COM_EASYBLOG_REMEMBER_ME') ?>
													</label>
												</div>
											<?php } ?>
										</div>
										<div class="popbox-dropdown-signin__action-col">
											<button class="btn btn-primary" tabindex="34"><?php echo JText::_('COM_EASYBLOG_LOGIN') ?></button>
										</div>
									</div>
									<input type="hidden" value="com_users"  name="option">
									<input type="hidden" value="user.login" name="task">
									<input type="hidden" name="return" value="<?php echo $return; ?>" />
									<?php echo $this->html('form.token'); ?>

									<?php if ($this->config->get('integrations_jfbconnect_login')) { ?>
										<?php echo EB::jfbconnect()->getTag();?>
									<?php } ?>
								</form>
							</div>
							<div class="popbox-dropdown__ft">
								<ul class=" popbox-dropdown-signin__ft-list g-list-inline g-list-inline--dashed t-text--center">
									<li>
										<a href="<?php echo EB::getRemindUsernameLink();?>"><?php echo JText::_('COM_EASYBLOG_FORGOTTEN_USERNAME');?></a>
									</li>
									<li>
										<a href="<?php echo EB::getResetPasswordLink();?>" class=""><?php echo JText::_( 'COM_EASYBLOG_FORGOTTEN_PASSWORD' );?></a>
									</li>
								</ul>
							</div>
						</div>
					</div>

				</div>
				<?php } ?>

				<?php if ($this->isMobile()) { ?>
					<?php if ($this->my->id) { ?>
					<div class="o-nav__item">
						<a class="o-nav__link eb-navbar__icon-link" href="javascript:void(0);" data-eb-toolbar-dashboard-toggle>
							<i class="fa fa-cog"></i>
						</a>
					</div>
					<?php } ?>

					<div class="hide" data-eb-mobile-dashboard-toolbar>
						<div class="eb-navbar-dashboard">
							<div class="eb-navbar-dashboard__list">
								<?php if ($this->config->get('layout_dashboardmain') && $this->acl->get('add_entry')) { ?>
								<div class="eb-navbar-dashboard__item">
									<a href="<?php echo EB::_('index.php?option=com_easyblog&view=dashboard');?>">
										<span><?php echo JText::_('COM_EASYBLOG_DASHBOARD_TOOLBAR_OVERVIEW');?></span>
									</a>
								</div>
								<hr>								
								<?php } ?>

								<?php if ($showManage) { ?>			
									<div class="eb-navbar-dashboard__item">
										<span><?php echo JText::_('COM_EASYBLOG_DASHBOARD_TOOLBAR_MANAGE');?></span>
									</div>

									<?php if ($this->acl->get('add_entry')) { ?>
									<div class="eb-navbar-dashboard__item">
										<a href="<?php echo EB::_('index.php?option=com_easyblog&view=dashboard&layout=entries');?>">
											<span><?php echo JText::_('COM_EASYBLOG_TOOLBAR_MANAGE_POSTS');?></span>
										</a>
									</div>
									<?php } ?>

									<?php if ($this->acl->get('create_post_templates')) { ?>
									<div class="eb-navbar-dashboard__item">
										<a href="<?php echo EB::_('index.php?option=com_easyblog&view=dashboard&layout=templates');?>">
											<span><?php echo JText::_('COM_EASYBLOG_DASHBOARD_HEADING_POST_TEMPLATES');?></span>
										</a>
									</div>
									<?php } ?>

									<?php if (EB::isSiteAdmin() || $this->acl->get('moderate_entry')) { ?>
									<div class="eb-navbar-dashboard__item">
										<a href="<?php echo EB::_('index.php?option=com_easyblog&view=dashboard&layout=moderate');?>">
											<span><?php echo JText::_('COM_EASYBLOG_TOOLBAR_MANAGE_PENDING');?></span>
										</a>
									</div>
									<?php } ?>
									<?php if ($this->acl->get('manage_comment') && EB::comment()->isBuiltin()) { ?>
									<div class="eb-navbar-dashboard__item">
										<a href="<?php echo EB::_('index.php?option=com_easyblog&view=dashboard&layout=comments');?>">
											<span><?php echo JText::_('COM_EASYBLOG_TOOLBAR_MANAGE_COMMENTS');?></span>
										</a>
									</div>
									<?php } ?>
									<?php if ($this->acl->get('create_category')) { ?>
									<div class="eb-navbar-dashboard__item">
										<a href="<?php echo EB::_('index.php?option=com_easyblog&view=dashboard&layout=categories');?>">
											<span><?php echo JText::_('COM_EASYBLOG_TOOLBAR_MANAGE_CATEGORIES');?></span>
										</a>
									</div>
									<?php } ?>
									<?php if ($this->acl->get('create_tag')) { ?>
									<div class="eb-navbar-dashboard__item">
										<a href="<?php echo EB::_('index.php?option=com_easyblog&view=dashboard&layout=tags');?>">
											<span><?php echo JText::_('COM_EASYBLOG_TOOLBAR_MANAGE_TAGS');?></span>
										</a>
									</div>
									<?php } ?>
									<?php if ($this->config->get('layout_teamblog') && $this->acl->get('create_team_blog')) { ?>
									<div class="eb-navbar-dashboard__item">
										<a href="<?php echo EB::_('index.php?option=com_easyblog&view=dashboard&layout=teamblogs');?>">
											<span><?php echo JText::_('COM_EASYBLOG_TOOLBAR_TEAMBLOGS');?></span>
										</a>
									</div>
									<?php } ?>
									<?php if ((EB::isTeamAdmin() || EB::isSiteAdmin()) && $this->config->get('toolbar_teamrequest')){ ?>
									<div class="eb-navbar-dashboard__item">
										<a href="<?php echo EBR::_('index.php?option=com_easyblog&view=dashboard&layout=requests');?>">
											<span><?php echo JText::_('COM_EASYBLOG_TOOLBAR_TEAM_REQUESTS');?></span>
										</a>
									</div>
									<hr>
									<?php } ?>
								<?php } ?>

								<div class="eb-navbar-dashboard__item">
									<span><?php echo JText::_('COM_EASYBLOG_YOUR_ACCOUNT'); ?></span>
								</div>

								<?php if ($this->acl->get('allow_subscription')) { ?>
								<div class="eb-navbar-dashboard__item">
									<a href="<?php echo EB::_('index.php?option=com_easyblog&view=subscription');?>">
										<span><?php echo JText::_('COM_EASYBLOG_TOOLBAR_MANAGE_SUBSCRIPTIONS');?></span>
									</a>
								</div>
								<?php } ?>
								<?php if ($this->config->get('toolbar_editprofile')){ ?>
								<div class="eb-navbar-dashboard__item">
									<a href="<?php echo EB::getEditProfileLink();?>">
										<span><?php echo JText::_('COM_EASYBLOG_TOOLBAR_EDIT_PROFILE');?></span>
									</a>
								</div>
								<?php } ?>
								<?php if ($this->config->get('toolbar_logout')){ ?>
								<div class="eb-navbar-dashboard__item">
									<a href="javascript:void(0);" data-blog-toolbar-logout>
										<span><?php echo JText::_('COM_EASYBLOG_TOOLBAR_SIGN_OUT');?></span>
									</a>
								</div>
								<?php } ?>
							</div>
						</div>
					</div>
				<?php } ?>

				<?php if ($this->config->get('layout_option_toolbar') && !$this->my->guest && !$this->isMobile()) { ?>
				<div class="o-nav__item dropdown_">

					<a href="javascript:void(0);" class="o-nav__link eb-navbar__icon-link dropdown-toggle_"
						data-bp-toggle="dropdown"
						data-original-title="<?php echo JText::_('COM_EASYBLOG_TOOLBAR_SETTINGS_TIPS');?>"
						data-placement="top"
						data-eb-provide="tooltip"
						role="button" aria-expanded="false"
					>
						<i class="fa fa-cog"></i>
						<span class="eb-navbar__link-text"><?php echo JText::_('COM_EASYBLOG_TOOLBAR_SETTINGS');?></span>
					</a>
					 <div id="more-settings" role="menu" class="eb-navbar__dropdown-menu dropdown-menu bottom-right">
						<div class="eb-arrow"></div>
						<div class="popbox-dropdown">
							<div class="popbox-dropdown__hd">
								<div class="popbox-dropdown__hd-flag">
									<div class="popbox-dropdown__hd-body">
										<?php if ($this->acl->get('add_entry')) { ?>
											<a href="<?php echo $this->profile->getPermalink();?>" class="eb-user-name"><?php echo $this->profile->getName();?></a>
										<?php } else { ?>
											<?php echo $this->profile->getName();?>
										<?php } ?>

										<?php if ($this->config->get('layout_dashboardmain') && $this->acl->get('add_entry')) { ?>
										<div class="mt-5">
											<a href="<?php echo EB::_('index.php?option=com_easyblog&view=dashboard');?>" class="text-muted">
												<?php echo JText::_('COM_EASYBLOG_DASHBOARD_TOOLBAR_OVERVIEW');?>
											</a>
										</div>
										<?php } ?>
									</div>
									<div class="popbox-dropdown__hd-image">
										<?php if ($this->acl->get('add_entry')) { ?>
										<a href="<?php echo $this->profile->getPermalink();?>" class="o-avatar o-avatar--sm">
											<img src="<?php echo $this->profile->getAvatar();?>" alt="<?php echo $this->html('string.escape', $this->profile->getName());?>" width="24" height="24" />
										</a>
										<?php } else { ?>
											<img src="<?php echo $this->profile->getAvatar();?>" alt="<?php echo $this->html('string.escape', $this->profile->getName());?>" width="24" height="24" />
										<?php } ?>
									</div>
								</div>
							</div>

							<div class="popbox-dropdown__bd">
								<div class="popbox-dropdown-nav">

									<?php if ($showManage) { ?>
									<div class="popbox-dropdown-nav__item ">
										<span class="popbox-dropdown-nav__link">

											<div class="popbox-dropdown-nav__name mb-5">
												<?php echo JText::_('COM_EASYBLOG_DASHBOARD_TOOLBAR_MANAGE');?>
											</div>

											<ol class="popbox-dropdown-nav__meta-lists">
												<?php if ($this->acl->get('add_entry')) { ?>
												<li>
													<a href="<?php echo EB::_('index.php?option=com_easyblog&view=dashboard&layout=entries');?>">
														<?php echo JText::_('COM_EASYBLOG_TOOLBAR_MANAGE_POSTS');?>
													</a>
												</li>
												<?php } ?>

												<?php if ($this->acl->get('create_post_templates')) { ?>
												<li>
													<a href="<?php echo EB::_('index.php?option=com_easyblog&view=dashboard&layout=templates');?>">
														<?php echo JText::_('COM_EASYBLOG_DASHBOARD_HEADING_POST_TEMPLATES');?>
													</a>
												</li>
												<?php } ?>

												<?php if (EB::isSiteAdmin() || $this->acl->get('moderate_entry')) { ?>
												<li>
													<a href="<?php echo EB::_('index.php?option=com_easyblog&view=dashboard&layout=moderate');?>">
														<i class="fa fa-ticket"></i> <?php echo JText::_('COM_EASYBLOG_TOOLBAR_MANAGE_PENDING');?>
														<?php if ($totalPending) { ?>
														<span class="popbox-dropdown-nav__indicator ml-5"></span>
														<span class="popbox-dropdown-nav__counter"><?php echo $totalPending;?></span>
														<?php } ?>
													</a>
												</li>
												<?php } ?>

												<?php if ($this->acl->get('manage_comment') && EB::comment()->isBuiltin()) { ?>
												<li>
													<a href="<?php echo EB::_('index.php?option=com_easyblog&view=dashboard&layout=comments');?>">
														<i class="fa fa-comments"></i> <?php echo JText::_('COM_EASYBLOG_TOOLBAR_MANAGE_COMMENTS');?>
														<?php if ($totalPendingComments) { ?>
														<span class="popbox-dropdown-nav__indicator ml-5"></span>
														<span class="popbox-dropdown-nav__counter"><?php echo $totalPendingComments; ?></span>
														<?php } ?>
													</a>
												</li>
												<?php } ?>

												<?php if ($this->acl->get('create_category')) { ?>
												<li>
													<a href="<?php echo EB::_('index.php?option=com_easyblog&view=dashboard&layout=categories');?>">
														<i class="fa fa-folder-o"></i> <?php echo JText::_('COM_EASYBLOG_TOOLBAR_MANAGE_CATEGORIES');?>
													</a>
												</li>
												<?php } ?>

												<?php if ($this->acl->get('create_tag')) { ?>
												<li>
													<a href="<?php echo EB::_('index.php?option=com_easyblog&view=dashboard&layout=tags');?>">
														<i class="fa fa-tags"></i> <?php echo JText::_('COM_EASYBLOG_TOOLBAR_MANAGE_TAGS');?>
													</a>
												</li>
												<?php } ?>

												<?php if ($this->config->get('layout_teamblog') && $this->acl->get('create_team_blog')) { ?>
												<li>
													<a href="<?php echo EB::_('index.php?option=com_easyblog&view=dashboard&layout=teamblogs');?>">
														<?php echo JText::_('COM_EASYBLOG_TOOLBAR_TEAMBLOGS');?>
													</a>
												</li>
												<?php } ?>

												<?php if ((EB::isTeamAdmin() || EB::isSiteAdmin()) && $this->config->get('toolbar_teamrequest') && $this->acl->get('create_team_blog')){ ?>
												<li>
													<a href="<?php echo EBR::_('index.php?option=com_easyblog&view=dashboard&layout=requests');?>">
														<i class="fa fa-users"></i> <?php echo JText::_('COM_EASYBLOG_TOOLBAR_TEAM_REQUESTS');?>
														<?php if ($totalTeamRequests) { ?>
														<span class="popbox-dropdown-nav__indicator ml-5"></span>
														<span class="popbox-dropdown-nav__counter"><?php echo $totalTeamRequests;?></span>
														<?php } ?>
													</a>
												</li>
												<?php } ?>
											</ol>
										</span>
									</div>
									<?php } ?>

									<div class="popbox-dropdown-nav__item ">
										<span class="popbox-dropdown-nav__link">
											<div class="popbox-dropdown-nav__name mb-5">
												<?php echo JText::_('COM_EASYBLOG_YOUR_ACCOUNT'); ?>
											</div>
											<ol class="popbox-dropdown-nav__meta-lists">
												<?php if ($this->acl->get('allow_subscription')) { ?>
												<li>
													<a href="<?php echo EB::_('index.php?option=com_easyblog&view=subscription');?>">
														<i class="fa fa-envelope"></i> <?php echo JText::_('COM_EASYBLOG_TOOLBAR_MANAGE_SUBSCRIPTIONS');?>
													</a>
												</li>
												<?php } ?>

												<?php if ($this->config->get('toolbar_editprofile')){ ?>
												<li>
													<a href="<?php echo EB::getEditProfileLink();?>">
														<?php echo JText::_('COM_EASYBLOG_TOOLBAR_EDIT_PROFILE');?>
													</a>
												</li>
												<?php } ?>

												<?php if ($this->config->get('toolbar_logout')){ ?>
												<li>
													<a href="javascript:void(0);" data-blog-toolbar-logout>
														<i class="fa fa-power-off"></i> <?php echo JText::_('COM_EASYBLOG_TOOLBAR_SIGN_OUT');?>
													</a>
												</li>
												<?php } ?>
											</ol>
										</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php } ?>
			</nav>

			<?php if ($this->config->get('layout_search')) { ?>
			<div id="eb-toolbar-search" class="eb-navbar__search <?php echo $this->isMobile() ? 'hide' : '';?>" data-eb-mobile-search>
				<form class="eb-navbar__search-form" method="post" action="<?php echo JRoute::_('index.php');?>">

					<input type="text" name="query" class="eb-navbar__search-input" autocomplete="off" placeholder="<?php echo JText::_('COM_EASYBLOG_TOOLBAR_PLACEHOLDER_SEARCH');?>" />
					<?php echo $this->html('form.action', 'search.query');?>

				</form>
			</div>
			<?php } ?>

			<form action="<?php echo JRoute::_('index.php');?>" method="post" data-blog-logout-form class="hide">
				<input type="hidden" value="com_users"  name="option" />
				<input type="hidden" value="user.logout" name="task" />
				<input type="hidden" value="<?php echo $return; ?>" name="return" />
				<?php echo $this->html('form.token'); ?>
			</form>
		</div>

		<?php if ($showFooter) { ?>
		<div class="eb-navbar__footer">
			<div class="o-row">
				<ol class="g-list-inline g-list-inline--dashed eb-navbar__footer-submenu">
					<?php if ($this->config->get('layout_latest')) { ?>
					<li class="<?php echo ($views->latest) ? 'is-active' : ''; ?>">
						<a href="<?php echo EBR::_('index.php?option=com_easyblog');?>" class="eb-navbar__footer-link">
							<i class="fa fa-home"></i>
							<span><?php echo JText::_('COM_EASYBLOG_TOOLBAR_LATEST_POSTS');?></span>
						</a>
					</li>
					<?php } ?>

					<?php if ($this->config->get('layout_categories')) { ?>
					<li class="<?php echo $views->categories ? 'is-active' : '';?>">
						<a href="<?php echo EBR::_('index.php?option=com_easyblog&view=categories');?>" class="eb-navbar__footer-link">
							<span><?php echo JText::_('COM_EASYBLOG_TOOLBAR_CATEGORIES');?></span>
						</a>
					</li>
					<?php } ?>

					<?php if ($this->config->get('layout_tags')) { ?>
					<li class="<?php echo $views->tags ? 'is-active' : '';?>">
						<a href="<?php echo EBR::_('index.php?option=com_easyblog&view=tags');?>" class="eb-navbar__footer-link">
							<span><?php echo JText::_('COM_EASYBLOG_TOOLBAR_TAGS');?></span>
						</a>
					</li>
					<?php } ?>

					<?php if ($this->config->get('layout_bloggers') && !$bloggerMode) { ?>
					<li class="<?php echo $views->blogger ? 'is-active' : '';?>">
						<a href="<?php echo EBR::_('index.php?option=com_easyblog&view=blogger');?>" class="eb-navbar__footer-link">
							<span><?php echo JText::_('COM_EASYBLOG_TOOLBAR_BLOGGERS');?></span>
						</a>
					</li>
					<?php } ?>

					<?php if ($this->config->get('layout_teamblog')) { ?>
					<li class="<?php echo $views->teamblog ? 'is-active' : '';?>">
						<a href="<?php echo EBR::_('index.php?option=com_easyblog&view=teamblog');?>" class="eb-navbar__footer-link">
							<span><?php echo JText::_('COM_EASYBLOG_TOOLBAR_TEAMBLOGS');?></span>
						</a>
					</li>
					<?php } ?>

					<?php if ($this->config->get('layout_archives')) { ?>
					<li class="<?php echo $views->archive ? 'is-active' : '';?>">
						<a href="<?php echo EBR::_('index.php?option=com_easyblog&view=archive');?>" class="eb-navbar__footer-link">
							<span><?php echo JText::_('COM_EASYBLOG_TOOLBAR_ARCHIVES');?></span>
						</a>
					</li>
					<?php } ?>

					<?php if ($this->config->get('layout_calendar')) { ?>
					<li class="<?php echo $views->calendar ? 'is-active' : '';?>">
						<a href="<?php echo EBR::_('index.php?option=com_easyblog&view=calendar');?>" class="eb-navbar__footer-link">
							<span><?php echo JText::_('COM_EASYBLOG_TOOLBAR_CALENDAR');?></span>
						</a>
					</li>
					<?php } ?>
				</ol>
			</div>
		</div>
		<?php } ?>
	</div>
	<?php } ?>
</div>
<?php } ?>

<?php echo EB::renderModule('easyblog-after-toolbar'); ?>
