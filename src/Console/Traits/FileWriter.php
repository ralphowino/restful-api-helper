<?php

namespace Ralphowino\ApiStarter\Console\Traits;

trait FileWriter
{
    /**
     * Writes to the bottom of the class
     *
     * @param $file
     * @param $content
     */
    protected function writeToBottomOfClass($file, $content)
    {
        $position = strrpos(\File::get($file), "}");
        $this->putInFile($file, "\n\t" . $content . "\n}", $position, $position);
    }

    /**
     * Writes to the bottom of the file
     *
     * @param $file
     * @param $content
     */
    protected function writeToBottomOfFile($file, $content)
    {
        $this->putInFile($file, "\n" . $content . "\n", -1, -1);
    }

    /**
     * Writes to a specific position in a file
     *
     * @param $file
     * @param $content
     * @param $start
     * @param $end
     */
    protected function putInFile($file, $content, $start, $end)
    {
        \File::put($file, substr_replace(\File::get($file), $content, $start, $end));
    }
}