<?php

return [
    'requests' => [
        'prefix' => '',
        'suffix' => '',
        'path' => 'Http\\Requests'
    ],

    'migration' => [
        'prefix' => '',
        'suffix' => '',
        'path' => 'database\\migrations'
    ],

    'model' => [
        'prefix' => '',
        'suffix' => '',
        'path' => 'Data\\Models'
    ],

    'transformer' => [
        'prefix' => '',
        'suffix' => 'Transformer',
        'path' => 'Http\\Transformers'
    ],

    'controller' => [
        'prefix' => '',
        'suffix' => '',
        'path' => 'Http\\Controllers\\Api'
    ],

    'repository' => [
        'prefix' => '',
        'suffix' => '',
        'path' => 'Data\\Repositories'
    ],

    'routes' => [
        'prefix' => '',
        'suffix' => '',
        'path' => 'Http'
    ],

    'oauth' => [
        'controller' => [
            'prefix' => '',
            'suffix' => '',
            'path' => 'Http\\Controllers\\Oauth'
        ],

        'view' => [
            'prefix' => '',
            'suffix' => '',
            'path' => '..\\resources\\views\\oauth'
        ],

        'seeder' => [
            'prefix' => '',
            'suffix' => '',
            'path' => '..\\database\\seeds'
        ]
    ],
];