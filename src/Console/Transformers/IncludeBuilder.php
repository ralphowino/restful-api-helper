<?php

namespace Ralphowino\ApiStarter\Console\Transformers;

class IncludeBuilder
{
    /**
     * Create the PHP syntax for the given schema.
     *
     * @param  array $includes
     * @param $model
     * @return string
     */
    public function create($includes, $model)
    {
        $DummyTransformerIncludes = $this->createTransformerIncludes($includes);
        $DummyTransformerIncludesMethods = $this->createTransformerIncludesMethods($includes, $model);
        //dd($DummyTransformerIncludesMethods);
        return compact('DummyTransformerIncludesMethods', 'DummyTransformerIncludes');
    }

    /**
     * Create transformer includes
     *
     * @param $includes
     * @return string
     */
    protected function createTransformerIncludes($includes)
    {
        $includesArray = array_map(function($include) {
            return "'" . $include['name'] . "'";
        }, $includes);

        return implode(",", $includesArray);
    }

    /**
     * Create transformer include methods
     *
     * @param $includes
     * @param $model
     * @return string
     */
    private function createTransformerIncludesMethods($includes, $model)
    {
        $includeMethods = array_map(function($include) use ($model) {
            return $this->constructMethod($include['name'], $include['type'], $model);
        }, $includes);


        return implode("\r\n", $includeMethods);
    }

    /**
     * Construct an include method
     * @param $include
     * @param $type
     * @param $model
     * @return mixed
     */
    private function constructMethod($include, $type, $model)
    {
        $stub = file_get_contents(__DIR__ . '/../stubs/partials/includeMethod.stub');

        return $this->addInclude($stub, $include)->addModel($stub, $model)->addType($stub, $type);
    }

    /**
     * Add include to the construct method
     *
     * @param $stub
     * @param $include
     * @return $this
     */
    private function addInclude(&$stub, $include)
    {
        $stub = str_replace(
            'DummyIncludeCamel', camel_case($include), $stub
        );

        $stub = str_replace(
            'DummyIncludeStudly', studly_case($include), $stub
        );

        $stub = str_replace(
            'DummyIncludeSingleStudly', studly_case(str_singular($include)), $stub
        );

        return $this;
    }

    /**
     * Add model to construct method
     *
     * @param $stub
     * @param $model
     * @return $this
     */
    private function addModel(&$stub, $model)
    {
        $stub = str_replace(
            'DummyModelStudly', studly_case($model), $stub
        );

        $stub = str_replace(
            'DummyModelCamel', camel_case($model), $stub
        );

        return $this;
    }

    /**
     * Add type to the construct method
     * 
     * @param $stub
     * @param $type
     * @return string
     */
    private function addType(&$stub, $type)
    {
        $stub = str_replace(
            'DummyType', strtolower($type), $stub
        );

        return $stub;
    }
}