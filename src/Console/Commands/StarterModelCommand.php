<?php

namespace Ralphowino\ApiStarter\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;
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
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/../stubs/model.stub';
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
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        if (parent::fire() !== false) {
            if ($this->option('migration')) {
                $table = $this->getTableInput();

                $migrationVariables = ['name' => "create_{$table}_table", '--schema' => $this->getSchemaInput()];

                //Check if the model is to be archived
                if ($this->getArchiveInput()) {
                    $migrationVariables['--archive'] = true;
                }

                $this->call('starter:migration', $migrationVariables);
            }
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
            ->replaceArchive($stub, $this->getArchiveInput())
            ->replaceClass($stub, $name);
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
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        return trim($this->argument('name'));
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
     * Check if the model should archive
     *
     * @return bool
     */
    private function getArchiveInput()
    {
        return boolval($this->option('archive'));
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
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['archive', 'a', InputOption::VALUE_NONE, 'Adds soft delete to the model being created.'],
            ['migration', 'm', InputOption::VALUE_NONE, 'Create a new migration file for the model.'],
            ['schema', null, InputOption::VALUE_OPTIONAL, 'The fields of the model to create.'],
            ['table', null, InputOption::VALUE_OPTIONAL, 'Assigns the model a specific table for it.'],
            ['relationships', null, InputOption::VALUE_OPTIONAL, 'The model\'s relationship.'],
        ];
    }
}