<?php

namespace YOOtheme\Theme\Joomla;

use YOOtheme\EventSubscriber;
use YOOtheme\Theme\Customizer;

class CustomizerListener extends EventSubscriber
{
    protected $cookie;
    protected $inject = [
        'url' => 'app.url',
        'styles' => 'app.styles',
        'scripts' => 'app.scripts',
        'customizer' => 'theme.customizer',
        'session' => 'JFactory::getSession',
        'application' => 'JFactory::getApplication',
    ];

    public function onInit()
    {
        $input = $this->application->input;

        $this->cookie = hash_hmac('md5', $this->theme->template, $this->app['secret']);
        $this->theme->isCustomizer = $input->get('p') == 'customizer';

        $active = $this->theme->isCustomizer || $input->cookie->get($this->cookie);

        // override params
        if ($active) {

            $custom = $input->getBase64('customizer');
            $params = $this->session->get($this->cookie) ?: [];

            foreach ($params as $key => $value) {
                $this->theme->params->set($key, $value);
            }

            if ($custom && $data = json_decode(base64_decode($custom), true)) {

                foreach ($data as $key => $value) {

                    if (in_array($key, ['config', 'admin', 'user_id'])) {
                        $params[$key] = $value;
                    }

                    $this->theme->params->set($key, $value);
                }

                $this->session->set($this->cookie, $params);
            }
        }

        $this->theme['customizer'] = function () use ($active) {
            return new Customizer($active);
        };
    }

    public function onSite()
    {
        // is active?
        if (!$this->customizer->isActive()) {
            return;
        }

        // add assets
        $this->styles->add('customizer', 'platforms/joomla/assets/css/site.css');

        // add data
        $this->customizer->addData('id', $this->theme->id);
    }

    public function onAdmin()
    {
        // add assets
        $this->styles->add('customizer', 'platforms/joomla/assets/css/admin.css');
        $this->scripts->add('customizer', 'platforms/joomla/app/customizer.min.js', ['uikit', 'vue']);

        $user = \JFactory::getUser();

        // add data
        $this->customizer->mergeData([
            'id' => $this->theme->id,
            'cookie' => $this->cookie,
            'template' => basename($this->theme->path),
            'site' => $this->url->base().'/index.php',
            'root' => \JUri::base(true),
            'token' => \JSession::getFormToken(),
            'apikey' => ($installer = \JPluginHelper::getPlugin('installer', 'yootheme')) ? (new \JRegistry($installer->params))->get('apikey') : false,
            'admin' => $this->app['admin'],
            'user_id' => $user->id,
            'media' => [
                'path' => \JComponentHelper::getParams('com_media')->get('file_path'),
                'canCreate' => $user->authorise('core.manage', 'com_media') || $user->authorise('core.create', 'com_media'),
                'canDelete' => $user->authorise('core.manage', 'com_media') || $user->authorise('core.delete', 'com_media'),
            ],
        ]);
    }

    public function onView($event)
    {
        // add data
        if ($this->customizer->isActive() && $this->application->get('themeFile') != 'offline.php' && $data = $this->customizer->getData()) {
            $this->scripts->add('customizer-data', sprintf('var $customizer = %s;', json_encode($data)), 'customizer', 'string');
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'theme.init' => ['onInit', 10],
            'theme.site' => ['onSite', -5],
            'theme.admin' => 'onAdmin',
            'view' => 'onView'
        ];
    }
}
