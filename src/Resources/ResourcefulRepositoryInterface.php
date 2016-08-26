<?php

namespace Ralphowino\ApiStarter\Resources;

use Illuminate\Http\Request;

interface ResourcefulRepositoryInterface
{
    /**
     * @param $id
     * @return mixed
     */
    public function getByKey($id);

    /**
     * @param $field
     * @param $value
     * @param bool $fails
     * @return mixed
     */
    public function getByField($field,$value,$fails = true);

    /**
     * @param $limit
     * @param $archived
     * @return mixed
     */
    public function getPaginated($limit,$archived);

    /**
     * @param Request $request
     * @return mixed
     */
    public function create(Request $request);

    /**
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function update($id, Request $request);

    /**
     * @param $id
     * @return mixed
     */
    public function archive($id);

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id);

    /**
     * @param $id
     * @return mixed
     */
    public function restore($id);
}