<?php

namespace Ralphowino\ApiStarter\Console\Commands;

use Illuminate\Console\Command;
use Ralphowino\ApiStarter\Console\Traits\BuildClassTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeController extends Command
{
    use BuildClassTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'starter:controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make an api controller.';

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
        //Get the name for the controller
        $name = studly_case($this->argument('name'));
        
        //Check if the controller should be plain
        $plain = $this->option('plain');
        
        //Check if the controller should be repository
        $repository = $this->option('repository');
        
        //Check if the controller should be transformer
        $transformer = $this->option('transformer');

        //Build up the controller's content
        $content = '<?php ' . view('controller',
                compact('name', 'plain', 'repository', 'transformer'))
                ->render();

        //Generate the controller
        $this->generateFile($name . '.php', 'controller', $content);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('name', InputArgument::REQUIRED, "The controller's name."),
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('plain', null, InputOption::VALUE_OPTIONAL, 'Create a plain controller', false),
            array('repository', null, InputOption::VALUE_OPTIONAL, "The controller's repository", false),
            array('transformer', null, InputOption::VALUE_OPTIONAL, "The controller's transformer", false),
        );
    }
}
