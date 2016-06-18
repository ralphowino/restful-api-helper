<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Console\Traits\BuildClassTrait;

class MakeOauth extends Command
{
    use BuildClassTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:oauth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Builds the Oauth using the Authorization type';

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
    public function handle()
    {
        //Todo: Put all this in the documentation for the api
        //Pull in the packages
        //Add the service provider to the app.php
        //Add the aliases to the app.php        //Add the middleware as should
        //Publish the vendor for the package

        //Build the OauthController
        $this->buildOauthController();

        //Make the authorization form
        $this->generateAuthorizationForm();

        //Create the OauthClientSeeder
        $this->buildOauthClientSeeder();

        //Prepend the routes to the routes.php file
        $this->addRoutes();
    }

    /**
     * Build the Oauth Controller
     */
    public function buildOauthController()
    {
        $content = '<?php ' . view('stubs.oauth.controller')->render();

        $this->generateFile('OauthController.php', 'oauth.controller', $content);
    }

    /**
     * Generate the authorization form view
     */
    public function generateAuthorizationForm()
    {
        $content = view('stubs.oauth.authorization-form')->render();

        $this->generateFile('authorization-form.blade.php', 'oauth.view', $content);
    }

    /**
     * Builds the OauthClientSeeder
     */
    public function buildOauthClientSeeder()
    {
        $content = '<?php ' . view('stubs.oauth.seeder')->render();
        
        $this->generateFile('OauthClientsSeeder.php', 'oauth.seeder', $content);
    }

    /**
     * Adds the necessary routes
     */
    public function addRoutes()
    {
        $content = view('stubs.oauth.routes')->render();

        $this->addToFile('routes.php', 'oauth.routes', $content);
    }
}
