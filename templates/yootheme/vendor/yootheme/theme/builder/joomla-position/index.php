<?php

$config = [

    'name' => 'yootheme/builder-joomla-position',

    'builder' => 'joomla_position',

    'inject' => [

        'view' => 'app.view',
        'scripts' => 'app.scripts',
        'document' => 'JFactory::getDocument',

    ],

    'render' => function ($element) {

        $renderer = $this->document->loadRenderer('modules');

        return $element['content'] && $this->document->countModules((string) $element['content'])
            ? $this->view->render('@builder/joomla-position/template', compact('element', 'renderer'))
            : '';
    },

    'events' => [

        'theme.admin' => function () {
            $this->scripts->add('builder-joomla-position', '@builder/joomla-position/app/joomla-position.min.js', 'customizer-builder');
        }

    ],

    'config' => [

        'element' => true,
        'defaults' => [

            'layout' => 'stack',
            'breakpoint' => 'm',

        ],

    ],

];

return defined('_JEXEC') ? $config : false;
