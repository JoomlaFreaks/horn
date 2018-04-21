<?php

$config = [

    'name' => 'yootheme/builder-joomla-module',

    'builder' => 'joomla_module',

    'inject' => [

        'view' => 'app.view',
        'scripts' => 'app.scripts',

    ],

    'render' => function ($element) {

        $method = new ReflectionMethod('JModuleHelper', 'load');
        $method->setAccessible(true);

        foreach ($method->invoke(null) as $module) {
            if ($module->id === $element['module']) {
                $element->title = $module->title;
                $element->props = $module->config->merge($element->props, true);
                $element->content = JModuleHelper::renderModule($module);
                break;
            }
        }

        return $element->content ? $this->view->render('@builder/joomla-module/template', compact('element')) : '';
    },

    'events' => [

        'theme.admin' => function () {
            $this->scripts->add('builder-joomla-module', '@builder/joomla-module/app/joomla-module.min.js', 'customizer-builder');
        }

    ],

    'config' => [

        'element' => true,

    ],

];

return defined('_JEXEC') ? $config : false;
