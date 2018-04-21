<?php

defined('_JEXEC') or die();

$theme = JHtml::_('theme');

// Set HTML5 output
$this->setHtml5(true);

$site = $theme->get('site', []);

// Page
$attrs_page = [];
$attrs_page_container = [];

$attrs_page['class'][] = 'tm-page';

if ($site['layout'] == 'boxed') {

    $attrs_page['class'][] = $site['boxed.alignment'] ? 'uk-margin-auto' : '';

    $attrs_page_container['class'][] = 'tm-page-container';
    $attrs_page_container['class'][] = $site['boxed.padding'] ? 'tm-page-container-padding' : '';
    $attrs_page_container['style'][] = $site['boxed.media'] ? "background-image: url('{$site['boxed.media']}');" : '';

}

?>
<!DOCTYPE html>
<html lang="<?= $this->language ?>" dir="<?= $this->direction ?>" vocab="http://schema.org/">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="<?= $theme->get('favicon') ?>">
        <link rel="apple-touch-icon-precomposed" href="<?= $theme->get('touchicon') ?>">
        <jdoc:include type="head" />
    </head>
    <body class="<?= $theme->get('body_class')->join(' ') ?>">

        <?php if (strpos($theme->get('header.layout'), 'offcanvas') === 0 || $theme->get('mobile.animation') == 'offcanvas') : ?>
        <div class="uk-offcanvas-content">
        <?php endif ?>

        <?php if ($site['layout'] == 'boxed') : ?>
        <div<?= JHtml::_('attrs', $attrs_page_container) ?>>
        <?php endif ?>

        <div<?= JHtml::_('attrs', $attrs_page) ?>>

            <div class="tm-header-mobile uk-hidden@<?= $theme->get('mobile.breakpoint') ?>">
            <?= JHtml::_('render', 'header-mobile') ?>
            </div>

            <?php if ($this->countModules('toolbar-left') || $this->countModules('toolbar-right')) : ?>
            <div class="tm-toolbar uk-visible@<?= $theme->get('mobile.breakpoint') ?>">
                <div class="uk-container uk-flex uk-flex-middle <?= $site['toolbar_fullwidth'] ? 'uk-container-expand' : '' ?> <?= $site['toolbar_center'] ? 'uk-flex-center' : '' ?>">

                    <?php if ($this->countModules('toolbar-left') || ($site['toolbar_center'] && $this->countModules('toolbar-right'))) : ?>
                    <div>
                        <div class="uk-grid-medium uk-child-width-auto uk-flex-middle" uk-grid="margin: uk-margin-small-top">

                            <?php if ($this->countModules('toolbar-left')) : ?>
                            <jdoc:include type="modules" name="toolbar-left" style="cell" />
                            <?php endif ?>

                            <?php if ($site['toolbar_center'] && $this->countModules('toolbar-right')) : ?>
                            <jdoc:include type="modules" name="toolbar-right" style="cell" />
                            <?php endif ?>

                        </div>
                    </div>
                    <?php endif ?>

                    <?php if (!$site['toolbar_center'] && $this->countModules('toolbar-right')) : ?>
                    <div class="uk-margin-auto-left">
                        <div class="uk-grid-medium uk-child-width-auto uk-flex-middle" uk-grid="margin: uk-margin-small-top">
                            <jdoc:include type="modules" name="toolbar-right" style="cell" />
                        </div>
                    </div>
                    <?php endif ?>

                </div>
            </div>
            <?php endif ?>

            <?= JHtml::_('render', 'header') ?>

            <jdoc:include type="modules" name="top" style="section" />

            <?php if (!$theme->get('builder')) : ?>

            <div id="tm-main" class="tm-main uk-section uk-section-default" uk-height-viewport="expand: true">
                <div class="uk-container">

                    <?php
                        $grid = ['uk-grid']; $sidebar = $theme->get('sidebar', []);
                        $grid[] = $sidebar['gutter'] ? "uk-grid-{$sidebar['gutter']}" : '';
                        $grid[] = $sidebar['divider'] ? "uk-grid-divider" : '';
                    ?>

                    <div<?= JHtml::_('attrs', ['class' => $grid, 'uk-grid' => true]) ?>>
                        <div class="uk-width-expand@<?= $theme->get('sidebar.breakpoint') ?>">

                            <?php if ($site['breadcrumbs']) : ?>
                            <div class="uk-margin-medium-bottom">
                                <?= JHtml::_('section', 'breadcrumbs') ?>
                            </div>
                            <?php endif ?>

            <?php endif ?>

            <jdoc:include type="message" />
            <jdoc:include type="component" />

            <?= JHtml::_('section', 'builder') ?>

            <?php if (!$theme->get('builder')) : ?>

                        </div>

                        <?php if ($this->countModules('sidebar')) : ?>
                        <?= JHtml::_('render', 'sidebar') ?>
                        <?php endif ?>

                    </div>

                </div>
            </div>
            <?php endif ?>

            <jdoc:include type="modules" name="bottom" style="section" />

            <?= JHtml::_('builder', $theme->get('footer.content'), 'footer') ?>

        </div>

        <?php if ($site['layout'] == 'boxed') : ?>
        </div>
        <?php endif ?>

        <?php if (strpos($theme->get('header.layout'), 'offcanvas') === 0 || $theme->get('mobile.animation') == 'offcanvas') : ?>
        </div>
        <?php endif ?>

        <jdoc:include type="modules" name="debug" />

    </body>
</html>
