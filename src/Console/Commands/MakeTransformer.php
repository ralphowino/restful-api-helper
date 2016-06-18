<?php

namespace Ralphowino\ApiStarter\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Ralphowino\ApiStarter\Console\Traits\BuildClassTrait;

class MakeTransformer extends Command
{
    use BuildClassTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'starter:transformer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make your own api transformer';

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
        //Get the name for the model
        $modelName = studly_case($this->argument('model'));
        //Get the model's path
        $modelPath = config('starter.model.path', '\\Data\\Models\\');

        $content = '<?php ' . view('transformer', compact('modelName', 'modelPath', 'name'))->render();
        $this->generateFile(ucwords($modelName). 'Transformer' . '.php', 'transformer', $content);

    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('model', InputArgument::REQUIRED, "The transformer's model's name."),
        );
    }
}
