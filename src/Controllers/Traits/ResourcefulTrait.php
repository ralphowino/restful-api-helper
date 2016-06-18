<?php

namespace App\Http\Controllers\Api\Traits;

trait ResourcefulTrait
{
    use ReadResourceTrait, StoreResourceTrait, UpdateResourceTrait, DeleteResourceTrait;
    
    /**
     * Controller repository
     */
    protected $repository;

    /**
     * Controller's transformer
     */
    protected $transformer;
}