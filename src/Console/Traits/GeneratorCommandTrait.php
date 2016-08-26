<?php

namespace Ralphowino\ApiStarter\Console\Traits;

trait GeneratorCommandTrait
{
    /**
     * Add the parent class to the class
     *
     * @param $stub
     * @param $type
     * @return $this
     */
    protected function addExtendClass(&$stub, $type)
    {
        //Fetch the path for the Base Class
        $basePath = config('starter.' . $type . '.extends');

        //Get the class credentials
        $baseClass = basename($basePath);
        $baseClassImport = dirname($basePath);

        //Check if we need to explicitly import the BaseClass
        if ($baseClassImport == trim(config('starter.' . $type . '.path'), '\\')) {
            $baseClassImport = '';
        } else {
            $baseClassImport = "use " . $basePath . "; \n";
        }

        //Add the Base class to the class
        $stub = str_replace(
            'DummyBaseClassImport', $baseClassImport, $stub
        );

        $stub = str_replace(
            'DummyBaseClass', $baseClass, $stub
        );

        return $this;
    }

    /**
     * Get the configurable namespace of the class
     *
     * @param $rootNamespace
     * @param $type
     * @return string
     */
    protected function getConfiguredNamespace($rootNamespace, $type)
    {
        if (config('starter.' . $type . '.path') != '')
            return $rootNamespace . '\\' . config('starter.' . $type . '.path');
        return $rootNamespace;
    }
}