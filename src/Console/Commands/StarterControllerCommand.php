<?php

namespace Ralphowino\ApiStarter\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Ralphowino\ApiStarter\Console\Traits\GeneratorCommandTrait;

class StarterControllerCommand extends GeneratorCommand
{
    use GeneratorCommandTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'starter:controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new controller class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Controller';

    /**
     * The controller's fields
     *
     * @var array
     */
    protected $fields = [];

    /**
     * The controller's repository
     *
     * @var string
     */
    protected $repository = '';

    /**
     * The controller's transformer
     *
     * @var string
     */
    protected $transformer = '';

    /**
     * Clean the user provided array of comma-separated options
     *
     * @param  $options_string
     * @return array
     */
    public function cleanUserOptionArray($options_string)
    {
        return array_map('trim', array_map('strtolower', explode(",", $options_string)));
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        if ($this->option('plain')) {
            return (file_exists(base_path('templates/controller.plain.stub'))) ? base_path('templates/controller.plain.stub') : __DIR__.'/../stubs/controller.plain.stub';
        }

        return (file_exists(base_path('templates/controller.stub'))) ? base_path('templates/controller.stub') : __DIR__.'/../stubs/controller.stub';
    }

    /**
     * Get the controller partials
     *
     * @param $name
     * @return string
     */
    protected function getPartial($name)
    {
        if(in_array('all', $this->fields) || in_array($name, $this->fields)){
            return $this->files->get((file_exists(base_path('templates/partials/'. $name .'.stub'))) ? base_path('templates/partials/'. $name .'.stub') : __DIR__.'/../stubs/partials/'. $name .'.stub');
        }

        return '';
    }

    /**
     * Get the controller constructor
     *
     * @return string
     */
    public function getConstructor()
    {
        $constructor =  $this->files->get(
            (file_exists(base_path('templates/partials/constructor.stub'))) ?
                base_path('templates/partials/constructor.stub') :
                __DIR__ . '/../stubs/partials/constructor.stub');

        return $constructor;
    }

    /**
     * Try determine the name of the controller's model
     *
     * @var string
     */
    public function getPossibleModelName()
    {
        return str_replace(['controller', 'Controller'], '', $this->getNameInput());
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $this->getConfiguredNamespace($rootNamespace, strtolower($this->type));
    }

    /**
     * Determine the controller's repository
     *
     * @param bool $automate
     * @return void
     */
    public function determineControllerRepository($automate = false)
    {
        $repository_name = str_plural($this->getPossibleModelName()) . 'Repository'; // Build default repository name

        if (! $this->option('repository')) {
            $this->repository = $this->ask('What is the repository for this controller?', $repository_name);
        } else {
            $this->repository = $this->option('repository');
        }

        if ($automate) {
            $this->call('starter:repository', ['name' => $this->repository]);
        }
    }

    /**
     * Determine the controller's transformer
     *
     * @param bool $automate
     */
    public function determineControllerTransformer($automate = false)
    {
        $transformer_name = str_plural($this->getPossibleModelName()) . 'Transformer'; // Build default transformer name

        if (! $this->option('transformer')) {
            $this->transformer = $this->ask('What is the transformer for this controller?', $transformer_name);
        } else {
            $this->transformer = $this->option('transformer');
        }

        if ($automate) {
            $this->call('starter:transformer', ['name' => $this->transformer]);
        }
    }

    /**
     *  Determine the methods to have in the controller
     *
     *  @return void
     */
    public function determineControllerMethods()
    {
        $possible_methods = ['index', 'show', 'store', 'update', 'destroy']; //Available controller methods

        if ($this->option('only')) {
            $this->fields = array_intersect($possible_methods, $this->cleanUserOptionArray($this->option('only')));

        } elseif ($this->option('except')) {
            $this->fields = array_diff($possible_methods, $this->cleanUserOptionArray($this->option('except')));

        } elseif ($this->option('resource')) {
            $this->fields = $possible_methods;

        } else {
            $fields = array_merge(['all'], $possible_methods);
            $this->fields = $this->choice('Select the methods you want in your controller', $fields, 0, null, true);
        }
    }

    /**
     * Execute the console command.
     *
     * @return bool|null
     */
    public function fire()
    {
        $automate = $this->option('automate');

        if (! $this->option('plain')) {
            $this->determineControllerMethods();

            $this->determineControllerRepository($automate);

            $this->determineControllerTransformer($automate);
        }

        return parent::fire();
    }

    /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in base namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = parent::buildClass($name);

        return $this->addConstructor($stub)
                    ->addExtendClass($stub, strtolower($this->type))
                    ->addRepository($stub)
                    ->addTransformer($stub)
                    ->addStoreMethod($stub)
                    ->addIndexMethod($stub)
                    ->addShowMethod($stub)
                    ->addUpdateMethod($stub)
                    ->addDestroyMethod($stub);
    }

    /**
     * Add the controller constructor
     *
     * @return $this
     */
    protected function addConstructor(&$stub)
    {
        $stub = str_replace(
            '{{constructor}}', $this->getConstructor(), $stub
        );

        return $this;
    }

    /**
     * Add the transformer
     *
     * @return $this
     */
    protected function addTransformer(&$stub)
    {
        $stub = str_replace(
            '{{import-transformer}}', $this->getLaravel()->getNamespace() . config('starter.transformer.path') . '\\' . $this->transformer, $stub
        );

        $stub = str_replace(
            '{{transformer-class}}', studly_case($this->transformer), $stub
        );

        $stub = str_replace(
            '{{transformer-variable}}', camel_case($this->transformer), $stub
        );

        return $this;
    }

    /**
     * Add the repository
     *
     * @return $this
     */
    protected function addRepository(&$stub)
    {
        $stub = str_replace(
            '{{import-repository}}', $this->getLaravel()->getNamespace() . config('starter.repository.path') . '\\' . $this->repository, $stub
        );

        $stub = str_replace(
            '{{repository-class}}', studly_case($this->repository), $stub
        );

        $stub = str_replace(
            '{{repository-variable}}', camel_case($this->repository), $stub
        );

        return $this;
    }

    /**
     * Add the index method to the controller
     *
     * @param $stub
     * @return $this
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function addIndexMethod(&$stub)
    {
        $stub = str_replace(
            '{{index-method}}', $this->getPartial('index'), $stub
        );

        return $this;
    }

    /**
     * Add the store method to the controller
     *
     * @param $stub
     * @return $this
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function addStoreMethod(&$stub)
    {

        $stub = str_replace(
            '{{store-method}}', $this->getPartial('store'), $stub
        );

        return $this;
    }

    /**
     * Add the show method to the controller
     *
     * @param $stub
     * @return $this
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function addShowMethod(&$stub)
    {
        $stub = str_replace(
            '{{show-method}}', $this->getPartial('show'), $stub
        );

        return $this;
    }

    /**
     * Add the update method to the controller
     *
     * @param $stub
     * @return $this
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function addUpdateMethod(&$stub)
    {
        $stub = str_replace(
            '{{update-method}}', $this->getPartial('update'), $stub
        );

        return $this;
    }

    /**
     * Add the destroy method to the controller
     *
     * @param $stub
     * @return mixed $stub
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function addDestroyMethod(&$stub)
    {
        $stub = str_replace(
            '{{destroy-method}}', $this->getPartial('destroy'), $stub
        );

        return $stub;
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
            array('automate', '-a', InputOption::VALUE_NONE, 'Create all the classes necessary automatically'),
            array('except', null, InputOption::VALUE_OPTIONAL, 'Define what methods to exclude from controller'),
            array('only', null, InputOption::VALUE_OPTIONAL, 'Define the controller\'s methods'),
            array('plain', '-p', InputOption::VALUE_NONE, 'Include no methods to the generated controller'),
            array('repository', null, InputOption::VALUE_OPTIONAL, "Assign the controller a repository"),
            array('resource', '-r', InputOption::VALUE_NONE, 'Include all the resourceful methods to the controller'),
            array('transformer', null, InputOption::VALUE_OPTIONAL, "Assign the controller a transformer"),
        );
    }
}
