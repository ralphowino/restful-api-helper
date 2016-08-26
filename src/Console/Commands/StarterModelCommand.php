<?php

namespace Ralphowino\ApiStarter\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;
use Ralphowino\ApiStarter\Console\Migrations\SchemaParser;
use Ralphowino\ApiStarter\Console\Models\RelationsBuilder;
use Ralphowino\ApiStarter\Console\Models\RelationsParser;
use Ralphowino\ApiStarter\Console\Traits\GeneratorCommandTrait;
use Ralphowino\ApiStarter\Console\Traits\ModelTrait;
use Symfony\Component\Console\Input\InputOption;

class StarterModelCommand extends GeneratorCommand
{
    use GeneratorCommandTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'starter:model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new API model class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Model';

    /**
     * Check if the model should archive
     *
     * @return bool
     */
    private function getArchiveInput()
    {
        return boolval($this->option('soft-deletes'));
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $this->getConfiguredNamespace($rootNamespace, strtolower($this->type));
    }

    /**
     * Fetch the fillable fields for the model
     *
     * @return string
     */
    protected function getFillableFields()
    {
        if($this->option('fillable')) {
            $arrayFields = explode(",", $this->option('fillable'));

            return stringfy_array($arrayFields);
        }

        if($this->option('schema')) {
            $fieldSchema = (new SchemaParser())->parse($this->option('schema'));
            $arrayFields = [];
            foreach ($fieldSchema as $record){
                $arrayFields[] = $record['name'];
            }

            return stringfy_array($arrayFields);
        }

        return '';
    }

    /**
     * Get the desired class name from the input.
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
        return (file_exists(base_path('templates/model.stub'))) ? base_path('templates/model.stub') : __DIR__ . '/../stubs/model.stub';
    }

    /**
     * Gets the table name for the model
     *
     * @return string
     */
    private function getTableInput()
    {
        if (!is_null($name = $this->option('table'))) {
            return trim($this->option('table'));
        }

        return $table = Str::plural(Str::snake(class_basename($this->argument('name'))));
    }

    /**
     * Gets the model's schema input
     *
     * @return string
     */
    private function getSchemaInput()
    {
        return $this->option('schema');
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        if (parent::fire() !== false) {
            $this->createMigration();

            $this->createRepository();

            $this->createTransformer();
        }
    }

    /**
     * Build the class with the given name.
     *
     * @param  string $name
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        return $this
            ->addRelationships($stub)
            ->addExtendClass($stub, strtolower($this->type))
            ->replaceNamespace($stub, $name)
            ->replaceTable($stub, $this->getTableInput())
            ->replaceFillable($stub, $this->getFillableFields())
            ->replaceArchive($stub, $this->getArchiveInput())
            ->replaceClass($stub, $name);
    }

    /**
     * Create the model's migration
     *
     * @return void
     */
    protected function createMigration()
    {
        if ($this->option('migration')) {
            $table = $this->getTableInput();

            $migrationVariables = ['name' => "create_{$table}_table", '--schema' => $this->getSchemaInput()];

            //Check if the model is to be archived
            if ($this->getArchiveInput()) {
                $migrationVariables['--soft-deletes'] = true;
            }

            $this->call('starter:migration', $migrationVariables);
        }
    }

    /**
     * Create the model's repository class
     *
     * @return void
     */
    protected function createRepository()
    {
        if ($this->option('repository')) {
            $model = ucwords($this->getNameInput());

            $this->call('starter:repository', ['name' => $model . 'Repository']);
        }
    }

    /**
     * Create the model's transformer class
     *
     * @return void
     */
    protected function createTransformer()
    {
        if ($this->option('transformer')) {
            $model = ucwords($this->getNameInput());

            $this->call('starter:transformer', ['name' => $model . 'Transformer']);
        }
    }

    /**
     * Replace the table for the given stub.
     *
     * @param $stub
     * @param $table
     * @return $this
     */
    private function replaceTable(&$stub, $table)
    {
        $stub = str_replace(
            'DummyTable', $table, $stub
        );

        return $this;
    }

    /**
     * Add soft delete to the given stub.
     *
     * @param $stub
     * @param $archive
     * @return $this
     */
    private function replaceArchive(&$stub, $archive)
    {
        if ($archive) {
            $stub = str_replace(
                "DummyArchiveUse", "use Illuminate\\Database\\Eloquent\\SoftDeletes;\n", $stub
            );

            $stub = str_replace(
                "DummyArchive", "use SoftDeletes;", $stub
            );

            return $this;
        }

        $stub = str_replace(
            "DummyArchiveUse", "", $stub
        );

        $stub = str_replace(
            "DummyArchive", "", $stub
        );

        return $this;

    }

    /**
     * Add the fillable fields to the model
     *
     * @param $stub
     * @param $fields
     * @return $this
     */
    private function replaceFillable(&$stub, $fields) {
        $stub = str_replace(
            "DummyFillableFields", $fields, $stub
        );

        return $this;
    }

    /**
     * Add the model's relationships
     *
     * @param $stub
     * @return mixed
     */
    protected function addRelationships(&$stub)
    {
        $builtRelationships = '';

        if ($relationships = $this->option('relationships')) {
            $parsedRelationships = (new RelationsParser())->parse($relationships);
            $builtRelationships = (new RelationsBuilder())->create($parsedRelationships);
        }

        $stub = str_replace(
            'DummyRelationships', $builtRelationships, $stub
        );


        return $this;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['migration', 'm', InputOption::VALUE_NONE, 'Create a new migration for the model.'],
            ['fillable', 'f', InputOption::VALUE_OPTIONAL, 'Define the fillable fields for the model'],
            ['repository', 'r', InputOption::VALUE_NONE, 'Define the model\'s repository'],
            ['relationships', null, InputOption::VALUE_OPTIONAL, 'Define the model\'s relationships'],
            ['schema', null, InputOption::VALUE_OPTIONAL, 'Define the fields of the model'],
            ['soft-deletes', 'a', InputOption::VALUE_NONE, 'Adds soft delete to the model'],
            ['table', null, InputOption::VALUE_OPTIONAL, 'Assigns the model a table'],
            ['transformer', 't', InputOption::VALUE_NONE, 'Define the model\'s transformer'],
        ];
    }
}