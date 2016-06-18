<?php

namespace Ralphowino\ApiStarter\Console\Traits;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

trait ProcessRunnerTrait
{

    /**
     * Run a command line command
     *
     * @param $command
     * @param bool $quiet
     */
    public function runProcess($command, $quiet = false)
    {
        //Execute the command
        $process = new Process($command);
        $process->run();

        //Executes after the command finishes
        if (!$process->isSuccessful()) {
            //Throw an error on failure of the process
            throw new ProcessFailedException($process);
        }

        //Output the process's output
        if(!$quiet) {
            echo $process->getOutput();
        }
    }
}