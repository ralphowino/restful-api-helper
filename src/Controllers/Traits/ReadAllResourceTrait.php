<?php

namespace App\Http\Controllers\Api\Traits;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

trait ReadAllResourceTrait
{

    /**
     * Display a listing of the collection.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $archived = $request->input('archived', false);
        $collection = $this->repository->sort($request)
            ->filter($request)
            ->getPaginated($request->get('per_page', 10), $archived);

        return $this->response()->paginator($collection, $this->transformer);
    }
}