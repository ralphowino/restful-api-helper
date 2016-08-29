<?php

namespace Ralphowino\ApiStarter\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Ralphowino\ApiStarter\Console\Traits\ResourceClassCreator;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Ralphowino\ApiStarter\Console\Transformers\FieldsParser;
use Ralphowino\ApiStarter\Console\Transformers\FieldsBuilder;
use Ralphowino\ApiStarter\Console\Transformers\IncludeBuilder;
use Ralphowino\ApiStarter\Console\Transformers\IncludesParser;
use Ralphowino\ApiStarter\Console\Traits\GeneratorCommandTrait;

class StarterTransformerCommand extends GeneratorCommand
{
    use GeneratorCommandTrait, ResourceClassCreator;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'starter:transformer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new transformer class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Transformer';

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $this->getConfiguredNamespace($rootNamespace, 'transformer');
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

        preg_match('/(.+)[Tt]ransformers?$/i', trim($name  = $this->argument('name')), $matches);

        if(count($matches) != 0) {
            return studly_case(strtolower(trim($matches[1])));
        }

        return studly_case(strtolower(trim($name)));
    }

    /**
     * Get the desired model name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        return trim($this->argument('name'));
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return file_exists(base_path('templates/transformer.stub')) ? base_path('templates/transformer.stub') : __DIR__.'/../stubs/transformer.stub';
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
                $this->call('starter:model', ['name' => $this->getModelInput()]);
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

        return $this->addModelNamespace($stub)
                    ->addModelName($stub)
                    ->addTransformerFields($stub)
                    ->addTransformerIncludes($stub);
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

        return $this;
    }

    /**
     * Add transformer fields
     *
     * @param $stub
     * @return $this
     */
    protected function addTransformerFields(&$stub)
    {
        if($fields = $this->option('fields')) {
            $parsedFields = (new FieldsParser())->parse($fields);
            $builtFields = (new FieldsBuilder())->create($parsedFields, camel_case($this->getModelInput()));
            $stub = str_replace(
                'DummyTransformerFields', $builtFields, $stub
            );
            return $this;
        }

        $stub = str_replace(
            'DummyTransformerFields', ' ', $stub
        );

        return $this;
    }

    /**
     * Add the transformer's includes
     *
     * @param $stub
     * @return mixed
     */
    protected function addTransformerIncludes(&$stub)
    {
        if($includes = $this->option('includes')) {
            $parsedIncludes = (new IncludesParser())->parse($includes);
            $builtIncludes = (new IncludeBuilder())->create($parsedIncludes, camel_case($this->getModelInput()));

            $stub = str_replace(
                ['DummyTransformerIncludesMethods', 'DummyTransformerIncludes'] , $builtIncludes, $stub
            );
        }

        $stub = str_replace(
            'DummyTransformerIncludesMethods', '', $stub
        );

        $stub = str_replace(
            'DummyTransformerIncludes', '', $stub
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
            array('name', InputArgument::REQUIRED, "The name of the model the transformer is linked to."),
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
            ['fields', 'f', InputOption::VALUE_OPTIONAL, 'The fields for the transformer', null],
            ['includes', 'i', InputOption::VALUE_OPTIONAL, 'The includes for the transformer', null],
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'Creates the transformer\'s model', '0'],
        ];
    }
}
