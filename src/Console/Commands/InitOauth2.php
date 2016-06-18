<?php

namespace Ralphowino\ApiStarter\Console\Commands;

use Illuminate\Console\Command;
use Ralphowino\ApiStarter\Console\Traits\FileWizard;
use Ralphowino\ApiStarter\Console\Traits\ProcessRunnerTrait;

class InitOauth2 extends Command
{
    use ProcessRunnerTrait, FileWizard;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'starter:init:oauth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initializes the Oauth functionality for the application';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        //Add the migration and the package for oauth2.0 server
        //Publish the Oauth 2.0 server package
        //Will create the config file
        //Will add the migrations
        //Re-write the config file as we intend it to look like
        $this->info("Publishing the LucaDegasperi's OAuth2Server package...");
        $this->runProcess("php artisan vendor:publish", false);
        $this->info("Publishing complete!");


        //We need a way to add the service providers for the package and the necessary a
        //Publish all the files to their respective places
        $this->info('Starting to publish the files...');
        $this->publishes([
            base_path('packages\\ralphowino\\api-starter\\src\\Oauth\\Controllers') => app_path(config('starter.oauth.controller.path')),
            base_path('packages\\ralphowino\\api-starter\\src\\Oauth\\Models') => app_path(config('starter.model.path')),
            base_path('packages\\ralphowino\\api-starter\\src\\Oauth\\Requests') => app_path(config('starter.requests.path')),
            base_path('packages\\ralphowino\\api-starter\\src\\Oauth\\Requests') => app_path(config('starter.requests.path')),
            base_path('packages\\ralphowino\\api-starter\\src\\Oauth\\Seeders') => app_path(config('starter.oauth.seeder.path')),
            base_path('packages\\ralphowino\\api-starter\\src\\Oauth\\Views\\clients') => base_path(config('starter.view.path') . '\\clients'),
            base_path('packages\\ralphowino\\api-starter\\src\\Oauth\\Views\\oauth') => base_path(config('starter.view.path') . '\\oauth'),
        ]);
        $this->info('Publishing complete!');

        //Add the necessary methods to the User model
        $this->writeToBottomOfClass(
            app_path(config('starter.model.path')). '\\User.php', 
            view('oauth.user-client-relationship')->render()
        );

        //Add the routes
        $this->writeToBottomOfFile(
            app_path(config('starter.routes.path')). '\\routes.php',
            view('oauth.routes')->render()
        );
    }
}