<?php

namespace Ralphowino\ApiStarter\Console\Commands;

use Illuminate\Console\Command;
use Ralphowino\ApiStarter\Console\Traits\BuildClassTrait;
use Ralphowino\ApiStarter\Console\Traits\FileWizard;
use Ralphowino\ApiStarter\Console\Traits\ProcessRunnerTrait;

class Initialization extends Command
{
    use BuildClassTrait, ProcessRunnerTrait, FileWizard;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'starter:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This initializes the restful api starter application.';

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
        //Provide user with message once the publishing has began
        $this->info('Initializing the stater package....');

        //Run the publishing of the starter package
        $this->info('Copying all the necessary starter files...');
        $this->publishAllFiles();

        //Copy the BaseController content to existing Controller
        $this->addToBaseController();

        //Append the routes to the routes.php file
        $this->info('Adding the routes to the routes file...');
        $this->addRoutes();

        //Initialization complete message
        $this->info('Initialization of the starter package complete!');
    }

    /**
     * Adds the necessary routes
     *
     * @return void
     */
    public function addRoutes()
    {
        //Get the routes template content
        $content = \File::get($this->package_path('Stubs\\routes.blade.php'));
        //Append to the routes.php file
        $this->addToFile('routes.php', 'routes', "\n\n" . $content);
    }

    /**
     * Publish all the necessary files for the
     * api starter package initialization
     *
     * @return void
     */
    public function publishAllFiles()
    {
        $this->publishes([
            $this->package_path('Controllers') => app_path('Http\\Controllers\\Api'),
            $this->package_path('Data\\Models') => app_path('Data\\Models'),
            $this->package_path('Data\\Repositories') => app_path('Data\\Repositories'),
            $this->package_path('Templates\\init-middleware.php') => app_path('Http\\Kernel.php'),
            $this->package_path('config\\view.php') => base_path('config\\view.php'),
        ]);
    }

    /**
     * Add the BaseController content
     *
     * @return void
     */
    public function addToBaseController()
    {
        $content = \File::get($this->package_path('Templates\\base-controller.php'));
        $this->writeToBottomOfClass(app_path('Http\\Controllers\\Controller.php'), $content);
    }
}