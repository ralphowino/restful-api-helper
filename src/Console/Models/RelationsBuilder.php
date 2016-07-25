<?php

namespace Ralphowino\ApiStarter\Console\Models;


class RelationsBuilder
{
    /**
     * Create the PHP syntax for the given schema.
     *
     * @param  array $relations
     * @return string
     */
    public function create($relations)
    {
        return $this->createRelationshipsMethods($relations);
    }

    /**
     * Create transformer relation methods
     *
     * @param $relations
     * @return string
     */
    private function createRelationshipsMethods($relations)
    {
        $relationMethods = array_map(function($relation) {
            return $this->constructMethod($relation['name'], $relation['relation']);
        }, $relations);


        return implode("\n", $relationMethods);
    }

    /**
     * Construct an relation method
     *
     * @param $name
     * @param $relation
     * @return string
     */
    private function constructMethod($name, $relation)
    {
        $stub = file_get_contents(file_exists('./templates/partials/relationshipMethod.stub') ? './templates/partials/relationshipMethod.stub' : __DIR__ . '/../stubs/partials/relationshipMethod.stub');

        return $this->addArguments($stub, $name, $relation)->addRelationship($stub, $relation)->addMethodName($stub, $name);
    }

    /**
     * Add the method's arguments
     *
     * @param $stub
     * @param $name
     * @param $relation
     * @return $this
     */
    protected function addArguments(&$stub, $name, $relation)
    {
        $stub = str_replace(
            'DummyRelationshipArguments', $this->getMethodArgument($name, $relation), $stub
        );

        return $this;
    }

    /**
     * Add the method relationship
     *
     * @param $stub
     * @param $relation
     * @return $this
     */
    protected function addRelationship(&$stub, $relation)
    {
        $stub = str_replace(
            'DummyRelationshipStudly', studly_case($relation), $stub
        );

        $stub = str_replace(
            'DummyRelationship', camel_case($relation), $stub
        );

        return $this;
    }

    /**
     * Add the name of the method
     *
     * @param $stub
     * @param $name
     * @return mixed
     */
    protected function addMethodName(&$stub, $name)
    {
        $stub = str_replace(
            'DummyMethod', camel_case($name), $stub
        );

        return $stub;
    }

    /**
     * Get the required method arguments
     *
     * @param $name
     * @param $relation
     * @return string
     */
    protected function getMethodArgument($name, $relation)
    {
        return studly_case(str_singular($name)) . '::class';
    }
}