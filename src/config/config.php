<?php

return [
    /*
     * The migration configuration settings.
     *
     * 1. Path - path to where the created migrations are stored
     */
    'migration' => [
        'path' => 'database\\migrations'
    ],

    /*
     * The model configuration settings
     *
     * 1. Path - path the models will be saved in.
     * 2. Extends -  The class all the created models will extend.
     */
    'model' => [
        'path' => 'Data\\Models',
        'extends' => 'Ralphowino\\ApiStarter\\Resources\\BaseModel'

    ],

    /*
     * The controller configuration settings
     *
     * 1. Path - path the controllers will be saved in.
     * 2. Extends - The class the created controller extends
     */
    'controller' => [
        'path' => 'Http\\Controllers\\Api',
        'extends' => 'Ralphowino\\ApiStarter\\Resources\\BaseController'
    ],

    /*
     * The repository configuration settings
     *
     * 1. Traits configuration settings
     *     1.1. Path - the path the repository traits will be saved in.
     * 2. Path - the path created repositories will be saved in.
     * 3. Extends - the class all created repositories will extend.
     */
    'repository' => [
        'trait' => [
            'path' => 'Data\\Repositories\\Traits'
        ],
        'path' => 'Data\\Repositories',
        'extends' => 'Ralphowino\\ApiStarter\\Resources\\BaseRepository'
    ],

    /*
     * The transformer configuration settings
     *
     * 1. Path - the path the created transformers will be saved in.
     */
    'transformer' => [
        'path' => 'Http\\Transformers'
    ],

    /*
     * The routes configuration settings
     *
     * 1. Path - the path the routes file is located.
     */
    'routes' => [
        'path' => 'Http'
    ],

    /*
     * The default classes for the application
     *
     * 1. Repository - the default repository for the controllers
     */
    'default' => [
        'repository' => 'UsersRepository',
        'transformer' => 'UsersTransformer'
    ]
];