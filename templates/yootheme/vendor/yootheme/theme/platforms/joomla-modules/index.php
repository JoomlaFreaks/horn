<?php

use YOOtheme\Theme\Builder;

$config = [

    'name' => 'yootheme/joomla-modules',

    'main' => 'YOOtheme\\Theme\\Modules',

    'inject' => [

        'db' => 'app.db',
        'view' => 'app.view',
        'styles' => 'app.styles',
        'scripts' => 'app.scripts',
        'document' => 'JFactory::getDocument',
        'language' => 'JFactory::getLanguage',

    ],

    'routes' => function ($routes) {

        $routes->get('/modules', function ($response) {
            return $response->withJson($this->modules);
        });

        $routes->get('/module', function ($id, $response) {

            $query = "SELECT id, content FROM @modules WHERE id = :id";
            $module = $this->db->fetchObject($query, ['id' => $id]);
            $module->content = json_decode($module->content, true);

            return $response->withJson($module);
        });

        $routes->post('/module', function ($id, $content, $response) {

            $this->db->update('@modules', [
                'content' => Builder::encode($content)
            ], ['id' => $id]);

            return $response->withJson(['message' => 'success']);
        });

        $routes->get('/positions', function ($response) {
            return $response->withJson($this->positions);
        });

    },

    'config' => [

        'section' => [
            'title' => 'Modules',
            'priority' => 40
        ],

        'fields' => [],

        'defaults' => [],

    ]

];

return defined('_JEXEC') ? $config : false;
