<?php

namespace App\Http\Controllers\Api\Traits;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

trait StoreResourceTrait
{

    /**
     * Store a newly created item in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validateInput('create');
        $item = $this->repository->create($request);
        return $this->response()->item($item, $this->transformer)
            ->setStatusCode(Response::HTTP_CREATED);
    }
}