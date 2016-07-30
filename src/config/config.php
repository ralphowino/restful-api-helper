<?php

return [
    'migration' => [
        'path' => 'database\\migrations'
    ],

    'model' => [
        'path' => 'Data\\Models',
        'extends' => 'Data\\Models\\Model'

    ],

    'controller' => [
        'path' => 'Http\\Controllers',
        'extends' => 'Http\\Controllers\\Controller'
    ],

    'repository' => [
        'trait' => [
            'path' => 'Data\\Repositories\\Traits'
        ],
        'path' => 'Data\\Repositories',
        'extends' => 'Data\\Repositories\\BaseRepository'
    ],

    'transformer' => [
        'path' => 'Http\\Transformers'
    ],

    'routes' => [
        'path' => 'Http'
    ]
];