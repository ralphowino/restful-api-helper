<?php

namespace App\Http\Controllers\Api\Traits;

use Illuminate\Http\Response;

trait RestoreResourceTrait
{

    /**
     * Restore an archived resource
     * 
     * @param $id
     * @return Response
     */
    public function restore($id)
    {
        $item = $this->repository->restore($id);
        return $this->response->item($item, new $this->transformer)->setMeta(['message' => 'Restored resource.']);
    }
}