<?php

namespace Ralphowino\ApiStarter\Console\Traits;

trait BuildClassTrait
{
    /**
     * Writes to the specified file
     *
     * @param $filePath
     * @param $fileContent
     * @param $type
     * @param string $base
     */
    public function writeToFile($filePath, $fileContent, $type, $base='app')
    {
        $fileMethod = $base.'_path';
        $directory = $fileMethod(config('starter.' . $type . '.path'));

        if(!file_exists($directory)) {
            mkdir($directory, 0777);
        }

        \File::put($filePath, $fileContent);
    }

    /**
     * Allows one to generate a file
     *
     * @param $fileName
     * @param $config
     * @param $content
     * @param string $base
     */
    public function generateFile($fileName, $config, $content, $base='app')
    {
        if (!$this->checkIfFileExists($filePath = $this->getFilePath($fileName, $config, $base))) {

            //Create the file
            $this->writeToFile($filePath, $content, $config, $base);
        }
    }

    /**
     * Check if file exists
     * 
     * @param $filePath
     * @return bool
     */
    protected function checkIfFileExists($filePath)
    {
        return file_exists($filePath);
    }

    /**
     * Get the file name
     *
     * @param $modelName
     * @param $type
     * @return string
     */
    protected function getFileName($modelName, $type)
    {
        return config('starter.' . $type . '.prefix') . $modelName . config('starter.' . $type . '.suffix') . '.php';
    }

    /**
     * Get the file path for the type of class
     *
     * @param $fileName
     * @param $type
     * @param $base
     * @return string
     */
    protected function getFilePath($fileName, $type, $base='app')
    {
        if($base == 'base')
        {
            return base_path(config('starter.' . $type . '.path') . '\\'. $fileName);
        }

        return app_path(config('starter.' . $type . '.path') . '\\'. $fileName);
    }

    /**
     * Append content to a specified file
     *
     * @param $routesFilePath
     * @param $fileContent
     * @param $type
     */
    protected function appendToFile($routesFilePath, $fileContent, $type)
    {
        if(!file_exists(app_path(config('starter.' . $type . '.path')))) {
            mkdir(app_path(config('starter.' . $type . '.path')), 0777);
        }

        \File::append($routesFilePath, $fileContent);
    }

    /**
     * Append the content to a file
     * 
     * @param $fileName
     * @param $config
     * @param $content
     */
    protected function addToFile($fileName, $config, $content)
    {
        if ($this->checkIfFileExists($filePath = $this->getFilePath($fileName, $config))) {
            //Append the file
            $this->appendToFile($filePath, $content, $config);
        }
    }
}