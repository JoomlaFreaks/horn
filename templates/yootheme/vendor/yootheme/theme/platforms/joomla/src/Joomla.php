<?php

namespace YOOtheme\Theme;

use YOOtheme\EventSubscriberInterface;
use YOOtheme\Module;
use YOOtheme\Theme\Joomla\ChildThemeListener;
use YOOtheme\Theme\Joomla\ContentListener;
use YOOtheme\Theme\Joomla\CustomizerListener;

class Joomla extends Module implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke($app)
    {
        $app->subscribe(new ContentListener)
            ->subscribe(new CustomizerListener)
            ->subscribe(new ChildThemeListener);

        $app['locator']->addPath("{$this->path}/assets", 'assets');
    }

    public function onInit($theme)
    {
        $this->language->load('tpl_yootheme', $this->theme->path);
        $this->document->setBase(htmlspecialchars(\JUri::current()));

        $this->url->addResolver(function ($path, $parameters, $secure, $next) {

            $uri = $next($path, $parameters, $secure, $next);
            $query = $uri->getQueryParams();

            if (isset($query['p']) && strpos($query['p'], 'theme/') === 0) {

                $query['option'] = 'com_ajax';
                $query['style'] = $this->theme->id;

                $uri = $uri->withQueryParams($query);
            }

            return $uri;
        });

        if (!$this->app['admin'] && !$this->theme->isCustomizer && $this->document->getType() == 'html') {
            $this->app->trigger('theme.site', [$this->theme]);
        }
    }

    public function onSite($theme)
    {
        require "{$this->theme->path}/html/helpers.php";

        $this->theme->set('direction', $this->document->direction);
        $this->theme->set('site_url', rtrim(\JUri::root(), '/'));
        $this->theme->set('page_class', $this->application->getParams()->get('pageclass_sfx'));

        if ($this->customizer->isActive()) {
            \JHtml::_('behavior.keepalive');
            $this->conf->set('caching', 0);
        }

        $this->builder->addRenderer(function ($element, $type, $next) {

            $result = $next($element, $type);

            if ($element->type == 'layout') {
                $result = \JHtmlContent::prepare($result);
            }

            return $result;
        });
    }

    public function onDispatch()
    {
        if (!$this->sections->exists('builder') && null !== $data = $this->theme->get('builder')) {
            $this->sections->set('builder', function () use ($data) {
                $result = $this->builder->render($data['content'], 'page').$data['edit'];
                $this->app->trigger('content', [$result]);
                return $result;
            });
        }

        if ($this->sections->exists('builder')) {
            $this->theme->set('builder', true);
            $this->document->setBuffer('', 'component');
        }
    }

    public function onContentData($context, $data)
    {
        if ($context == 'com_templates.style') {
            $params = ['style' => $data->id];
        } elseif ($context == 'com_content.article' && $data->id) {

            \JLoader::register('ContentHelperRoute', JPATH_SITE . '/components/com_content/helpers/route.php');

            $route = \JRouter::getInstance('site')->build(\ContentHelperRoute::getArticleRoute($data->id, $data->catid));

            $params = [
                'section' => 'builder',
                'site' => $this->admin ? preg_replace('/\/administrator/', '', $route, 1) : (string) $route,
            ];

        } else {
            return;
        }

        $this->scripts
            ->add('$customizer', 'platforms/joomla/app/customizer.js', '$customizer-data')
            ->add('$customizer-data', sprintf('var $customizer = %s;', json_encode([
                'context' => $context,
                'apikey' => ($installer = \JPluginHelper::getPlugin('installer', 'yootheme')) ? (new \JRegistry($installer->params))->get('apikey') : false,
                'url' => $this->app->url(($this->app['admin'] ? 'administrator/' :  '') . 'index.php?p=customizer&option=com_ajax', $params),
            ])), [], 'string');
    }

    public static function getSubscribedEvents()
    {
        return [
            'theme.init' => ['onInit', -15],
            'theme.site' => ['onSite', 10],
            'dispatch' => 'onDispatch',
            'content.data' => 'onContentData',
        ];
    }
}
