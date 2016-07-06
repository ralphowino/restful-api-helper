<?php

namespace Ralphowino\ApiStarter\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Ralphowino\ApiStarter\Console\Traits\GeneratorCommandTrait;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

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
     * @var
     */
    protected $fields = [];

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        if ($this->option('plain')) {
            return __DIR__.'/../stubs/controller.plain.stub';
        }

        return __DIR__.'/../stubs/controller.stub';
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
            return $this->files->get(__DIR__.'/../stubs/partials/'.$name .'.stub');
        }

        return '';
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
        if(!$this->option('plain')) {
            $fields = ['all', 'index', 'show', 'store', 'update', 'destroy'];
            $this->fields = $this->choice('Select the methods you want in your controller', $fields, 0, null, true);
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

        return $this->addExtendClass($stub, strtolower($this->type))
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
            array('plain', '-p', InputOption::VALUE_NONE, 'Create a plain controller')
        );
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
