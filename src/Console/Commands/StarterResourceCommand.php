<?php

namespace Ralphowino\ApiStarter\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Ralphowino\ApiStarter\Console\Traits\GeneratorCommandTrait;

class StarterResourceCommand extends GeneratorCommand
{
    use GeneratorCommandTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'starter:resource';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new resource for the application';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Resource';

    /**
     * The name of the resource to be generated
     *
     * @var string
     */
    protected $resource;

    /**
     * Build the controller variables array
     *
     * @return array
     */
    public function getControllerVariables()
    {
        // Set the controller variables to an empty array
        $controllerVariables = [];

        // Set the controller name
        $controllerVariables['name'] = $this->getControllerInput();

        // Set the repository name
        $controllerVariables['--repository'] = $this->getRepositoryInput();

        // Set the transformer name
        $controllerVariables['--transformer'] = $this->getTransformerInput();

        // Select the controller methods
        if($this->option('only')) {
            // Set the only controller methods to be created
            $controllerVariables['--only'] = $this->option('only');
        } else if($this->option('except')) {
            // Exempt this methods from the controller generated
            $controllerVariables['--except'] = $this->option('except');
        } else {
            // Make the controller resourceful
            $controllerVariables['--resource'] = true;
        }

        // Return the controller variables array
        return $controllerVariables;

    }

    /**
     * Get the controller name input
     *
     * @return string
     */
    public function getControllerInput()
    {
        if (!is_null($name = $this->option('controller'))) {
            return trim($name);
        }

        return studly_case($this->resource) . 'Controller';
    }

    /**
     * Build the model variables array
     *
     * @return array
     */
    public function getModelVariables()
    {
        // Set the model variables to an empty array
        $modelVariables = [];

        // Set the model name
        $modelVariables['name'] = $this->getModelInput();

        // Set the resources table
        if ($this->option('table')) {
            $modelVariables['--table'] = trim($this->option('table'));
        }

        // Set the resource's schema
        if($this->option('schema')){
            $modelVariables['--migration'] = true;
            $modelVariables['--schema'] = $this->getSchemaInput();
        }

        // Set the resources's relationships
        if($this->option('relationships')) {
            $modelVariables['--relationships'] = $this->getRelationshipsInput();
        }

        // Add soft deletion to the resource
        if($this->option('soft-deletes')) {
            $modelVariables['--soft-deletes'] = true;
        }

        // Return the model variables
        return $modelVariables;
    }

    /**
     * Get the model name input
     *
     * @return string
     */
    public function getModelInput()
    {
        if (!is_null($name = $this->option('model'))) {
            return trim($name);
        }

        return studly_case($this->resource);
    }

    /**
     * Get the schema input
     *
     * @return array|string
     */
    public function getSchemaInput()
    {
        return $this->option('schema');
    }

    /**
     * Get the relationships input
     *
     * @return array|string
     */
    public function getRelationshipsInput()
    {
        return $this->option('relationships');
    }

    /**
     * Build the repository variables array
     *
     * @return array
     */
    public function getRepositoryVariables()
    {
        // Set the repository variables to an empty array
        $repositoryVariables = [];

        // Set the repository name
        $repositoryVariables['name'] = $this->getRepositoryInput();

        // Set the repository model
        $repositoryVariables['--model'] = $this->getModelInput();

        // Return the repository variables array
        return $repositoryVariables;
    }

    /**
     * Get the repository name input
     *
     * @return string
     */
    public function getRepositoryInput()
    {
        if (!is_null($name = $this->option('repository'))) {
            return trim($name);
        }

        return studly_case($this->resource) . 'Repository';
    }

    /**
     * Build the transformer variables array
     *
     * @return array
     */
    public function getTransformerVariables()
    {
        // Set the transformer variables array to an empty array
        $transformerVariables = [];

        // Set the name for the transformer
        $transformerVariables['name'] = $this->getTransformerInput();

        // Set the transformer's model
        $transformerVariables['--model'] = $this->getModelInput();

        // Set the fields for the transformer
        $transformerVariables['--fields'] = $this->getTransformerFieldsInput();

        // Set the includes for the transformer
        $transformerVariables['--includes'] = $this->getTransformerIncludesInput();

        // Return the transformer variables
        return $transformerVariables;

    }

    /**
     * Get the transformer name input
     *
     * @return string
     */
    public function getTransformerInput()
    {
        if (!is_null($name = $this->option('transformer'))) {
            return trim($name);
        }

        return studly_case($this->resource) . 'Transformer';
    }

    /**
     * Get the transformer includes
     *
     * @return array|string
     */
    public function getTransformerFieldsInput()
    {
        return $this->option('fields');
    }

    /**
     * Get the transformer fields
     *
     * @return array|string
     */
    public function getTransformerIncludesInput()
    {
        return $this->option('includes');
    }

    /**
     * Execute the console command.
     *
     * @return bool|null
     */
    public function fire()
    {
        $this->resource = $this->getNameInput();

        // Create the model
        $modelVariables = $this->getModelVariables();
        $this->call('starter:model', $modelVariables);

        // Create the controller
        $controllerVariables = $this->getControllerVariables();
        $this->call('starter:controller', $controllerVariables);

        // Create the transformer
        $transformerVariables = $this->getTransformerVariables();
        $this->call('starter:transformer', $transformerVariables);

        // Create the repository
        $repositoryVariables = $this->getRepositoryVariables();
        $this->call('starter:repository', $repositoryVariables);

        // Todo: Add the rules for the resource [create-rules, update-rules]
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('name', InputArgument::REQUIRED, "The name of the resource."),
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
            array('controller', null, InputOption::VALUE_OPTIONAL, 'Link to a specific controller'),
            array('fields', null, InputOption::VALUE_OPTIONAL, 'Define the fields that the transformer will provide'),
            array('includes', null, InputOption::VALUE_OPTIONAL, 'Define the transformer\'s includes'),
            array('model', null, InputOption::VALUE_OPTIONAL, 'Link to a specific model'),
            array('only', null, InputOption::VALUE_OPTIONAL, 'Define controller methods to create'),
            array('except', null, InputOption::VALUE_OPTIONAL, 'Define controller methods not to create'),
            array('model', null, InputOption::VALUE_OPTIONAL, 'Link to a specific model'),
            array('relationships', null, InputOption::VALUE_OPTIONAL, 'This are the relationships for the resource'),
            array('repository', null, InputOption::VALUE_OPTIONAL, 'Link to a specific repository'),
            array('schema', null, InputOption::VALUE_OPTIONAL, 'This is the schema for the resource'),
            array('soft-deletes', null, InputOption::VALUE_NONE, 'Adds soft deletion to the resource'),
            array('table', null, InputOption::VALUE_OPTIONAL, 'Define the resource\'s table'),
            array('transformer', null, InputOption::VALUE_OPTIONAL, 'Link to a specific transformer'),
        );
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        // TODO: Implement getStub() method.
    }
}
