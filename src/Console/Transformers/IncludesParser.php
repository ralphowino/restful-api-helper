<?php

namespace Ralphowino\ApiStarter\Console\Transformers;

class IncludesParser
{
    /**
     * The parsed includes.
     *
     * @var array
     */
    private $includes = [];

    /**
     * Parse the command line migration schema.
     * Ex: user:item, tasks:collection
     *
     * @param  string $includes
     * @return array
     */
    public function parse($includes)
    {
        $fields = $this->splitIntoFields($includes);

        foreach ($fields as $field) {
            $segments = $this->parseSegments($field);

            $this->addInclude($segments);
        }

        return $this->includes;
    }

    /**
     * Add a include to the transformer array.
     *
     * @param  array $include
     * @return $this
     */
    private function addInclude($include)
    {
        $this->includes[] = $include;

        return $this;
    }

    /**
     * Get an array of fields from the given schema.
     *
     * @param  string $includes
     * @return array
     */
    private function splitIntoFields($includes)
    {
        return preg_split('/,\s?(?![^()]*\))/', $includes);
    }

    /**
     * Get the segments of the schema field.
     *
     * @param  string $field
     * @return array
     */
    private function parseSegments($field)
    {
        $segments = explode(':', $field);

        $name = array_shift($segments);
        $type = array_shift($segments);

        return compact('name', 'type');
    }
}