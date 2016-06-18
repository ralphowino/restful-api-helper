<?php

namespace App\Http\Controllers\Api\Traits;

use Illuminate\Http\Response;

trait ReadOneResourceTrait
{

    /**
     * Display the specified item.
     *
     * @param  int $id
     * @return Response
     **/
    public function show($id)
    {
        $item = $this->repository->getByKey($id, true);
        return $this->response()->item($item, $this->transformer);
    }
}