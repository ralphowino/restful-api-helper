<?php

namespace Ralphowino\ApiStarter\Console\Transformers;


class FieldsParser
{
    /**
     * The parsed fields.
     *
     * @var array
     */
    private $fields = [];

    /**
     * Parse the command line transformers fields.
     * Ex: name:string, age:integer:nullable
     *
     * @param  string $fields
     * @return array
     */
    public function parse($fields)
    {
        $fields = $this->splitIntoFields($fields);

        foreach ($fields as $field) {
            $this->addField($field);
        }

        return $this->fields;
    }

    /**
     * Add a field to the fields array.
     *
     * @param  array $field
     * @return $this
     */
    private function addField($field)
    {
        $this->fields[] = $field;

        return $this;
    }

    /**
     * Get an array of fields from the given fields.
     *
     * @param  string $fields
     * @return array
     */
    private function splitIntoFields($fields)
    {
        return preg_split('/,\s?(?![^()]*\))/', $fields);
    }
}