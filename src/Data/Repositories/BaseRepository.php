<?php

namespace App\Data\Repositories;

class BaseRepository
{
    /**
     * Resolve the query variable
     * 
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        if ($name == 'query') {
            if (!isset($this->query) || $this->query === null) {
                if (method_exists($this, 'query')) {
                    return $this->query();
                }
                return $this->model;
            }
            return $this->model;
        }
        return $this->model;
    }
}