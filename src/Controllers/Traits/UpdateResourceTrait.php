<?php

namespace App\Http\Controllers\Api\Traits;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

trait UpdateResourceTrait
{
    /**
     * Update the specified item in storage.
     *
     * @param  int $id
     * @param Request $request
     * @return Response
     */
    public function update($id, Request $request)
    {
        $this->validateInput('update');
        $item = $this->repository->update($id, $request);
        return $this->response->item($item, $this->transformer);
    }
}