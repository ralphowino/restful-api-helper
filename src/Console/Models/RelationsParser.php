<?php

namespace Ralphowino\ApiStarter\Console\Models;


class RelationsParser
{
    /**
     * The parsed relations.
     *
     * @var array
     */
    private $relations = [];

    /**
     * Parse the command line migration schema.
     * Ex: user:belongsTo, tasks:hasMany
     *
     * @param  string $relations
     * @return array
     */
    public function parse($relations)
    {
        $relationships = $this->splitIntoFields($relations);

        foreach ($relationships as $relationship) {
            $segments = $this->parseSegments($relationship);

            $this->addRelationship($segments);
        }

        return $this->relations;
    }

    /**
     * Add a relationship to the relationship array.
     *
     * @param  array $relation
     * @return $this
     */
    private function addRelationship($relation)
    {
        $this->relations[] = $relation;

        return $this;
    }

    /**
     * Get an array of fields from the given schema.
     *
     * @param  string $relations
     * @return array
     */
    private function splitIntoFields($relations)
    {
        return preg_split('/,\s?(?![^()]*\))/', $relations);
    }

    /**
     * Get the segments of the relationship.
     *
     * @param  string $relationship
     * @return array
     */
    private function parseSegments($relationship)
    {
        if(str_contains($relationship, ':')) {
            $segments = explode(':', $relationship);

            $name = array_shift($segments);
            $relation = array_shift($segments);
        } else {
            $name = $relationship;
            $relation = $this->determineRelationshipType($name);
        }

        return compact('name', 'relation');
    }

    /**
     * Determine the field type for the transformer relations
     *
     * @param $name
     * @return string
     */
    protected function determineRelationshipType($name)
    {
        if (str_plural($name) == $name) {
            return 'hasMany';
        } else {
            return 'belongsTo';
        }
    }
}