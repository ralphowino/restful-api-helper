<?php

namespace Ralphowino\ApiStarter\Console\Traits;

use Illuminate\Console\DetectsApplicationNamespace;

trait ResourceClassCreator
{
    use DetectsApplicationNamespace;
    /**
     * Return the path to the specified type of class
     *
     * @param $name
     * @param string $type
     * @return string
     */
    protected function getClassPath($name, $type = 'model')
    {
        $name = config('starter.'. $type .'.path') . '\\' . str_replace($this->getAppNamespace(), '', $name);
        return $this->laravel['path'] . '/' . str_replace('\\', '/', $name) . '.php';
    }
}
