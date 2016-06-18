<?php

namespace App\Http\Controllers\Api\Traits;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

trait DestroyResourceTrait
{

    /**
     * Remove the specified item from storage.
     *
     * @param  int $id
     * @return Response
     **/
    public function destroy($id, Request $request)
    {
        if (!$request->input('force')) {
            $record = $this->repository->archive($id);
            return $this->response()->item($record, $this->transformer);
        }
        $this->repository->delete($id);
        return $this->response->noContent();
    }
}