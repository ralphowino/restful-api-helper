<?php

namespace Ralphowino\ApiStarter;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Ralphowino\ApiStarter\Console\Commands\Initialization;
use Ralphowino\ApiStarter\Console\Commands\StarterModelCommand;
use Ralphowino\ApiStarter\Console\Commands\StarterResourceCommand;
use Ralphowino\ApiStarter\Console\Commands\StarterMigrationCommand;
use Ralphowino\ApiStarter\Console\Commands\StarterControllerCommand;
use Ralphowino\ApiStarter\Console\Commands\StarterRepositoryCommand;
use Ralphowino\ApiStarter\Console\Commands\StarterTransformerCommand;

class ApiStarterServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //Publish the starter config file.
        $this->publishes([
            __DIR__ . '/config/jwt.php' => base_path('config/jwt.php'),
            __DIR__ . '/config/api.php' => base_path('config/api.php'),
            __DIR__ . '/config/cors.php' => base_path('config/cors.php'),
            __DIR__ . '/config/config.php' => base_path('config/starter.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/Console/stubs' => base_path('templates')
        ], 'stubs');

        //Register the aliases
        $this->registerFacades();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //Register the service providers
        $this->registerServiceProviders();

        //Register the commands for the starter api starter
        $this->registerCommands();

        //Generate the jwt secret key
        $this->registerHelpers();
    }

    /**
     * Register the package's helper methods
     *
     * @return void
     */
    public function registerHelpers()
    {
        require_once __DIR__ . '/helpers.php';
    }

    /**
     * This adds the service providers for the packages used be the restful starter package
     *
     * @return void
     */
    public function registerServiceProviders()
    {
        //1. Dingo API
        $this->app->register('Dingo\Api\Provider\LaravelServiceProvider');
        //2. Jwt
        $this->app->register('Tymon\JWTAuth\Providers\JWTAuthServiceProvider');
        //3. CORS
        $this->app->register('Barryvdh\Cors\ServiceProvider');
    }

    /**
     * This adds the external package's aliases
     *
     * @return void
     */
    public function registerFacades()
    {
        //1. Dingo API
        AliasLoader::getInstance()->alias('API', 'Dingo\Api\Facade\API');
        //2. JWT
        AliasLoader::getInstance()->alias('JWTAuth', 'Tymon\JWTAuth\Facades\JWTAuth');
        AliasLoader::getInstance()->alias('JWTFactory', 'Tymon\JWTAuth\Facades\JWTFactory');
    }

    /**
     * Register the restful starter package commands
     *
     * @return void
     */
    public function registerCommands()
    {
        $this->commands([
            Initialization::class,
            StarterModelCommand::class,
            StarterResourceCommand::class,
            StarterMigrationCommand::class,
            StarterRepositoryCommand::class,
            StarterControllerCommand::class,
            StarterTransformerCommand::class,
        ]);
    }
}
