<?php

namespace App\Http\Controllers\Api\Traits;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

trait ReadResourceTrait
{
    use ReadAllResourceTrait, ReadOneResourceTrait;
}