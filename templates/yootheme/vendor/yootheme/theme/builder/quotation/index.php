<?php

return [

    'name' => 'yootheme/builder-quotation',

    'builder' => 'quotation',

    'inject' => [

        'view' => 'app.view',

    ],

    'render' => function ($element) {
        return $this->view->render('@builder/quotation/template', compact('element'));
    },

    'config' => [

        'element' => true,

    ],

    'default' => [

        'props' => [
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
        ],

    ],

];
