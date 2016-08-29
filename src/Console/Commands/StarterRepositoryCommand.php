<?php

namespace Ralphowino\ApiStarter\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Ralphowino\ApiStarter\Console\Traits\GeneratorCommandTrait;
use Ralphowino\ApiStarter\Console\Traits\ResourceClassCreator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class StarterRepositoryCommand extends GeneratorCommand
{
    use GeneratorCommandTrait, ResourceClassCreator;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'starter:repository';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Repository';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return file_exists(base_path('templates/repository.stub')) ? base_path('templates/repository.stub') : __DIR__.'/../stubs/repository.stub';
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
     * Get the model namespace
     *
     * @return string
     */
    protected function getModelNamespace()
    {
        return $this->laravel->getNamespace() . config('starter.model.path');
    }

    /**
     * Get the desired model name from the input.
     *
     * @return string
     */
    protected function getModelInput()
    {
        if($this->option('model')) {
            return trim($this->option('model'));
        }

        preg_match('/(.+)[Rr]epositor(?:ies|ys?)$/i', trim($name  = $this->argument('name')), $matches);

        if(count($matches) != 0) {
            return studly_case(strtolower(trim($matches[1])));
        }

        return str_singular(studly_case(strtolower(trim($name))));
    }

    /**
     * Get the desired repository name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        return $this->argument('name');
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        if (parent::fire() !== false) {
            if (!$this->files->exists($this->getClassPath($this->getModelInput()))) {
                $this->call('starter:model', ['name' =>$this->getModelInput()]);
            }
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

        return $this->addExtendClass($stub, strtolower($this->type))
                    ->addRepositoryName($stub)
                    ->addTraitNamespace($stub)
                    ->addModelNamespace($stub)
                    ->addModelName($stub);
    }

    /**
     * Add the repository's class name
     *
     * @param $stub
     * @return $this
     */
    public function addRepositoryName(&$stub)
    {
        $stub = str_replace(
            'DummyRepository', $this->getNameInput(), $stub
        );

        return $this;
    }

    /**
     * Add the model namespace to the stub
     *
     * @param $stub
     * @return $this
     */
    protected function addModelNamespace(&$stub)
    {
        $stub = str_replace(
            'DummyModelNamespace', $this->getModelNamespace(), $stub
        );

        return $this;
    }

    /**
     * Add the model names tot he stub
     *
     * @param $stub
     * @return $this
     */
    protected function addModelName(&$stub)
    {
        $model = $this->getModelInput();

        $stub = str_replace(
            'DummyModelCamel', camel_case($model), $stub
        );

        $stub = str_replace(
            'DummyModel', $model, $stub
        );

        return $stub;
    }

    /**
     * Add the trait's namespace to the repository
     *
     * @param $stub
     * @return $this
     */
    protected function addTraitNamespace(&$stub)
    {
        $stub = str_replace(
            'DummyTraitNamespace',
            $this->getConfiguredNamespace(trim($this->laravel->getNamespace(), '\\'), 'repository.trait'),
            $stub
        );

        return $this;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('name', InputArgument::REQUIRED, "The name of the model the repository is linked to."),
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'Define the model for the repository']
        ];
    }
}
