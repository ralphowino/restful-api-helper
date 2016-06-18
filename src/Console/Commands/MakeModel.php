<?php

namespace Ralphowino\ApiStarter\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Ralphowino\ApiStarter\Console\Traits\BuildClassTrait;

class MakeModel extends Command
{
    use BuildClassTrait;

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
    protected $description = 'This commands helps the user to make a model for the api';

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
        //Fetch the model's name
        $modelName = $this->argument('name');

        //Check if it uses soft deletes and adds soft deletes
        $usingSoftDelete = (boolean) $this->option('archive');

        //Create the model
        $this->buildsModel($modelName, $usingSoftDelete);

        //Check if user wants a migration with the model
        if((boolean) $this->option('migration'))
        {
            //Creates the model's migration
            $this->buildMigrationForModel($modelName, $usingSoftDelete);
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('name', InputArgument::REQUIRED, 'This is the name of the model.'),
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
            array('archive', null, InputOption::VALUE_OPTIONAL, 'Select if the model uses soft delete.', false),
            array('migration', null, InputOption::VALUE_OPTIONAL, 'Build the migration for the model.', false),
        );
    }

    /**
     * Build up a model
     *
     * @param $modelName
     * @param $archive
     * @throws \Exception
     * @throws \Throwable
     */
    private function buildsModel($modelName, $archive)
    {
        $content = '<?php '. view('model', array("modelName" => $modelName, "archive" => $archive))->render();
        $this->generateFile(ucwords($modelName) . '.php', 'model', $content);
    }

    /**
     * Builds up a migration for a model
     *
     * @param $modelName
     * @param $archive
     * @throws \Exception
     * @throws \Throwable
     */
    private function buildMigrationForModel($modelName, $archive)
    {
        //Append the date prefix as laravel custom
        $migrationName = $this->getDatePrefix() . '_create_' . str_plural(strtolower($modelName)) . '_table';
        $content = '<?php '. view('migration', array("modelName" => $modelName, "archive" => $archive))->render();
        //Generate the migration
        $this->generateFile($migrationName . '.php', 'migration', $content, 'base');
    }

    /**
     * Get the date prefix for the migration.
     *
     * @return string
     */
    protected function getDatePrefix()
    {
        return date('Y_m_d_His');
    }
}