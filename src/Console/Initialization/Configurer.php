<?php

namespace Ralphowino\ApiStarter\Console\Initialization;

use Illuminate\Console\Command;

class Configurer
{
    /**
     * The path to the config file to modify
     *
     * @var string
     */
    protected $config_path;

    /**
     * List of configurations
     *
     * @var array
     */
    protected $configurations = [];

    /**
     * List of fields to configure
     *
     * @var array
     */
    protected $fields;

    /**
     * The class calling this class
     *
     * @var Command
     */
    private $origin;

    /**
     * Configurer constructor.
     *
     * @param Command $origin
     * @param array $fields
     * @param string $config_path
     */
    public function __construct(Command $origin, $fields = [], $config_path = './config/starter.php')
    {
        $this->origin = $origin;
        $this->fields = $fields;
        $this->config_path = $config_path;
    }

    /**
     * Run the list of enquires about the configurations
     *
     * @return array
     */
    public function run()
    {
        foreach ($this->fields as $configuration => $value) {
            $this->configurations[$configuration] = $this->origin->ask($value['question'], $value['default']);
        }

        return $this->configurations;
    }

    /**
     * Save the content to the file
     *
     * @return void
     */
    public function save()
    {
        $config_content = file_get_contents($this->config_path);

        foreach ($this->fields as $configuration => $value) {
            $config_content = str_replace(str_replace('\\', '\\\\', $value['default']), str_replace('\\', '\\\\', $this->configurations[$configuration]), $config_content);
            config([$value['config'] => str_replace('\\', '\\\\', $this->configurations[$configuration])]); //Reset the config value
        }

        file_put_contents($this->config_path, $config_content);
    }
}