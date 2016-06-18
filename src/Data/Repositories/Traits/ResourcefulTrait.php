<?php

namespace App\Data\Repositories\Traits;

use Illuminate\Http\Request;
use App\Data\Repositories\Contracts\BaseInterface;

trait ResourcefulTrait
{
    /**
     * Get a collection of models
     *
     * @param bool $archived
     * @param null $withResource
     * @return mixed
     */
    public function getAll($archived = false, $withResource = null)
    {
        if (!is_null($withResource))
            $this->query = $this->query->with($withResource);

        if ($archived)
            return $this->query->latest($this->model->getTable().'.updated_at')->onlyTrashed()->get();

        return $this->query->latest($this->model->getTable().'.updated_at')->get();
    }


    /**
     * Gets a collection of models belonging to the ids passed.
     *
     * @param $ids
     * @param null $withResource
     * @return array
     */
    public function getManyByIds($ids, $withResource = null)
    {
        if (!is_null($withResource))
            $this->query = $this->query->with($withResource);

        if (empty($ids))
            return [];

        return $this->query->latest($this->model->getTable().'.updated_at')->whereIn($this->model->getTable() . '.id', $ids)->get();
    }

    /**
     * Get a model be a field
     *
     * @param $field
     * @param $value
     * @param bool|true $fails
     * @return mixed
     */
    public function getByField($field, $value, $fails = true)
    {
        if ($fails)
            return $this->model->latest($this->model->getTable().'.updated_at')->where($field, $value)->firstOrFail();
        else
            return $this->model->latest($this->model->getTable().'.updated_at')->where($field, $value)->first();
    }

    /**
     * Get a paginated collection of models
     *
     * @param int $per_page
     * @return mixed
     */
    public function getPaginated($per_page, $archived)
    {
        switch ($archived) {
            case 'true':
                return $this->query->latest($this->model->getTable().'.updated_at')->withTrashed()->paginate($per_page);
                break;
            case 'only':
                return $this->query->latest($this->model->getTable().'.updated_at')->onlyTrashed()->paginate($per_page);
                break;
            default:
                return $this->query->latest($this->model->getTable().'.updated_at')->paginate($per_page);
        }

    }

    /**
     * Create a new model
     *
     * @param Request $request
     * @return mixed
     */
    public function create(Request $request)
    {
        $record = $this->model->create(array_only($request->all(), $this->fields));
        return $record;
    }

    /**
     * Update an existing model
     *
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function update($id, Request $request)
    {
        $record = $this->getByKey($id);
        $record->update(array_only($request->all(), $this->fields));
        return $record;
    }

    /**
     * Get a model by key
     *
     * @param $key
     * @param bool $includeArchived
     * @return Model | null
     * @throws ModelNotFoundException
     */
    public function getByKey($key, $includeArchived = false)
    {
        if (is_int($includeArchived)) {
            switch ($includeArchived) {
                case BaseInterface::INCLUDE_ARCHIVED:
                    return $this->query->withTrashed()->findOrFail($key);
                    break;
                case
                    BaseInterface::INCLUDE_ARCHIVED | BaseInterface::RETURN_NULL_IF_NOT_FOUND :
                    return $this->query->withTrashed()->find($key);
                    break;
                case BaseInterface::RETURN_NULL_IF_NOT_FOUND:
                    return $this->query->find($key);
                    break;
                default:
                    return $this->query->findOrFail($key);
            }
        }

        if ($includeArchived)
            return $this->query->withTrashed()->findOrFail($key);
        return $this->query->findOrFail($key);

    }

    /**
     * Delete an existing model
     *
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        $record = $this->getByKey($id, true);
        return $record->forceDelete();
    }

    /**
     * Archive an existing model
     *
     * @param $id
     * @return mixed
     */
    public function archive($id)
    {
        $record = $this->getByKey($id);
        $record->delete();
        return $record;
    }

    /**
     * Permanently delete an record.
     *
     * @param $id
     * @return bool|null
     */
    public function forceDelete($id)
    {
        $record = $this->getByKey($id, true);
        return $record->forceDelete();
    }

    /**
     * Restore an archived model
     * 
     * @param $id
     * @return mixed
     */
    public function restore($id)
    {
        $record = $this->getByKey($id, true);
        $record->restore();
        return $record;
    }
}