<?php

namespace Ralphowino\ApiStarter\Console\Traits;

trait DirectoryWizard
{

    /**
     * Check if the directory exists and creates it if necessary
     *
     * @param $directory
     * @return mixed
     */
    protected function findOrCreateDirectory($directory)
    {
        //Check if the directory exists
        if (file_exists($directory)) {
            return $directory;
        }
        //Create the directory if it does not exists (Recursively and giving the directories the 0777 mode)
        return $this->createDirectory($directory, 0777, true);
    }

    /**
     * Creates the intended directory
     *
     * @param $directory
     * @param $mode
     * @param $recursively
     * @return mixed
     */
    protected function createDirectory($directory, $mode, $recursively)
    {
        mkdir($directory, $mode, $recursively);

        return $directory;
    }

    /**
     * Return the absolute path for package resources
     *
     * @param $path
     * @return string
     */
    protected function package_path($path)
    {
        return __DIR__. '/../../'. $path;
    }
}