<?php

namespace Ralphowino\ApiStarter\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Composer;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Ralphowino\ApiStarter\Console\Migrations\NameParser;
use Ralphowino\ApiStarter\Console\Migrations\SchemaParser;
use Ralphowino\ApiStarter\Console\Migrations\SyntaxBuilder;
use Ralphowino\ApiStarter\Console\Traits\ResourceClassCreator;

class StarterMigrationCommand extends Command
{
    use ResourceClassCreator;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'starter:migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new migration class and apply schema at the same time';

    /**
     * The filesystem instance.
     *
     * @var Filesystem
     */
    protected $files;

    /**
     * Meta information for the requested migration.
     *
     * @var array
     */
    protected $meta;

    /**
     * @var Composer
     */
    private $composer;

    /**
     * @var string
     */
    private $type;

    /**
     * Create a new command instance.
     *
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
        $this->composer = app()['composer'];
        $this->type = 'Migration';
    }

    /**
     * Check if the migration
     *
     * @return bool
     */
    private function getArchiveInput()
    {
        return boolval($this->option('soft-deletes'));
    }

    /**
     * Get the class name for the Eloquent model generator.
     *
     * @return string
     */
    protected function getModelName()
    {
        if ($this->option('model')) {
            return trim($this->option('model'));
        }

        return ucwords(str_singular(camel_case($this->meta['table'])));
    }

    /**
     * Get the path to where we should store the migration.
     *
     * @param  string $name
     * @return string
     */
    protected function getPath($name)
    {
        return base_path() . '/database/migrations/' . date('Y_m_d_His') . '_' . $name . '.php';
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->meta = (new NameParser)->parse($this->argument('name'));
        $this->makeMigration();
        $this->makeModel();
    }

    /**
     * Compile the migration stub.
     *
     * @return string
     */
    protected function compileMigrationStub()
    {
        $stub = $this->files->get((file_exists(base_path('templates/migration.stub'))) ? base_path('templates/migration.stub') : __DIR__ . '/../stubs/migration.stub');
        $this->replaceClassName($stub)
            ->replaceSchema($stub)
            ->replaceTableName($stub);
        return $stub;
    }

    /**
     * Generate the desired migration.
     */
    protected function makeMigration()
    {
        $name = $this->argument('name');
        if ($this->files->exists($path = $this->getPath($name))) {
            $this->error($this->type . ' already exists!');
            return false;
        }
        $this->makeDirectory($path);
        $this->files->put($path, $this->compileMigrationStub());
        $this->info('Migration created successfully.');
        $this->composer->dumpAutoloads();
    }

    /**
     * Generate an Eloquent model, if the user wishes.
     */
    protected function makeModel()
    {
        $modelPath = $this->getClassPath($this->getModelName());
        if ($this->option('model') && !$this->files->exists($modelPath)) {
            $modelParameters = [
                'name' => $this->getModelName(),
                '--table' => $this->meta['table'],
                '--schema' => $this->option('schema')
            ];

            if ($this->getArchiveInput()) {
                $modelParameters['--soft-deletes'] =  true;
            }

            $this->call('starter:model', $modelParameters);
        }
    }

    /**
     * Build the directory for the class if necessary.
     *
     * @param  string $path
     * @return string
     */
    protected function makeDirectory($path)
    {
        if (!$this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }
    }

    /**
     * Replace the class name in the stub.
     *
     * @param  string $stub
     * @return $this
     */
    protected function replaceClassName(&$stub)
    {
        $className = ucwords(camel_case($this->argument('name')));
        $stub = str_replace('{{class}}', $className, $stub);
        return $this;
    }

    /**
     * Replace the table name in the stub.
     *
     * @param  string $stub
     * @return $this
     */
    protected function replaceTableName(&$stub)
    {
        $table = $this->meta['table'];
        $stub = str_replace('{{table}}', $table, $stub);
        return $this;
    }

    /**
     * Replace the schema for the stub.
     *
     * @param  string $stub
     * @return $this
     */
    protected function replaceSchema(&$stub)
    {
        if ($schema = $this->option('schema')) {
            $schema = (new SchemaParser)->parse($schema);
        }
        $schema = (new SyntaxBuilder)->create($schema, $this->meta, $this->getArchiveInput());
        $stub = str_replace(['{{schema_up}}', '{{schema_down}}'], $schema, $stub);
        return $this;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the migration'],
        ];
    }
    
    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['schema', 's', InputOption::VALUE_OPTIONAL, 'Optional schema to be attached to the migration', null],
            ['model', null, InputOption::VALUE_OPTIONAL, 'Want a model for this table?', false],
            ['soft-deletes', null, InputOption::VALUE_NONE, 'Adds soft-delete to the created migration'],
        ];
    }
}