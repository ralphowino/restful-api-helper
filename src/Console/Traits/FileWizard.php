<?php

namespace Ralphowino\ApiStarter\Console\Traits;

trait FileWizard
{
    use DirectoryWizard, FileWriter;

    /**
     * Publish the list of records
     *
     * @param array $records
     */
    protected function publishes(array $records)
    {
        //Iterate through the passed records
        foreach ($records as $source => $destination)
        {
            //Copy file(s) to the destination
            $this->copy($destination, $source);
        }
    }

    /**
     * Copies file from one destination to another
     * 
     * @param $destination
     * @param $source
     * @return bool
     */
    protected function copy($destination, $source)
    {
        if(file_exists($source)) {
            if(is_dir($source)) {
                $files = \File::allFiles($source);
                foreach ($files as $file)
                {
                    $fileName = $file->getFilename();

                    if($file->getRelativePath() == "") {
                        $this->copyFileTo($destination, $source . '\\' . $fileName);
                    } else {
                        $this->copyFileTo($destination . '\\' . $file->getRelativePath(), $source . '\\' . $file->getRelativePath() . '\\' . $fileName);
                    }
                }
                return true;
            }

            $this->copyFileTo($destination, $source);
            return true;
        }
        return false;
    }

    /**
     * Copies a file to the described destination
     *
     * @param $destination
     * @param $source
     * @return bool
     */
    protected function copyFileTo($destination, $source)
    {
        $directory = $this->findOrCreateDirectory($destination);
        
        if(is_dir($directory)) {
            \File::put($directory . '\\' . basename($source), \File::get($source));
            return true;
        }
        //
        \File::put($directory, \File::get($source));
        return true;
    }
}