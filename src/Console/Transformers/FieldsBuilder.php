<?php

namespace Ralphowino\ApiStarter\Console\Transformers;

class FieldsBuilder
{
    /**
     * Create the PHP syntax for the given schema.
     *
     * @param $fields
     * @param $model
     * @return string
     */
    public function create($fields, $model)
    {
        $fields = $this->constructFields($fields, $model);

        return $fields;
    }


    /**
     * Construct the transformer fields.
     *
     * @param $fields
     * @param $model
     * @return array
     */
    private function constructFields($fields, $model)
    {
        if(!$fields) return '';

        $fieldsArray = [];

        if($fields) {
            $fieldsArray = array_map(function ($field) use ($model) {
                return "'" . $field . "'" . ' => $' .$model. '->' . $field . ',';
            }, $fields);
        }

        return implode("\n" . str_repeat(' ', 12), $fieldsArray);
    }
}