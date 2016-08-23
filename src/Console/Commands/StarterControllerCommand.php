<?php

namespace Ralphowino\ApiStarter\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
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
     * Execute the console command.
     *
     * @return bool|null
     */
    public function fire()
    {
        $this->determineControllerMethods();

        $this->determineControllerRepository();

        $this->determineControllerTransformer();

        return parent::fire();
    }

    /**
     * Determine the controller's repository
     *
     * @return void
     */
    public function determineControllerRepository()
    {
        if (! $this->option('repository')) {
            $this->repository = $this->ask('What is the repository for this controller?', config('starter.default.repository'));
        } else {
            $this->repository = $this->option('repository');
        }
    }

    /**
     * Determine the controller's transformer
     *
     * @return void
     */
    public function determineControllerTransformer()
    {
        if (! $this->option('transformer')) {
            $this->transformer = $this->ask('What is the transformer for this controller?', config('starter.default.transformer'));
        } else {
            $this->transformer = $this->option('transformer');
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
            $this->fields = array_intersect($possible_methods, explode(",", $this->option('only')));
        } elseif ($this->option('except')) {
            $this->fields = array_diff($possible_methods, explode(",", $this->option('except')));
        } elseif ($this->option('resourceful')) {
            $this->fields = $possible_methods;
        } elseif (!$this->option('plain')) {
            $fields = array_merge(['all'], $possible_methods);
            $this->fields = $this->choice('Select the methods you want in your controller', $fields, 0, null, true);
        }
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
            array('plain', '-p', InputOption::VALUE_NONE, 'Create a plain controller'),
            array('resourceful', '-r', InputOption::VALUE_NONE, 'Create a resourceful controller'),
            array('repository', null, InputOption::VALUE_OPTIONAL, "The controller's repository"),
            array('transformer', null, InputOption::VALUE_OPTIONAL, "The controller's transformer"),
            array('only', null, InputOption::VALUE_OPTIONAL, 'Create controller with only this methods'),
            array('except', null, InputOption::VALUE_OPTIONAL, 'Create controller without this methods')
        );
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
}
