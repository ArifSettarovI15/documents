<?php


namespace App\Repositories;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class BaseRepository
{
    /**
     * @var $model Model
     */
    public $model;

    /**
     * @param Request $request
     * @return mixed
     */

    public function change_status(Request $request)
    {
        return $this->model->find($request->id)->update([$request->field => $request->value]);
    }

    /**
     * @param $request
     * @return Model|Builder
     */

    public function new_item($request)
    {
        return $this->model->create($request->all());
    }

    /**
     * @param $id
     * @return Model
     */
    public function get_item($id): Model
    {
        $result =  $this->model->find($id);
        $result ?: abort(404);
        return $result;
    }

    /**
     * @param $id
     * @param $request
     * @return bool
     */
    public function update_item(int $id, $request): bool
    {
        return $this->model->find($id)->update($request->all());
    }

    /**
     * @param int $per_page
     * @param array $filters
     * @param string $order
     * @param string|null $column
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function get_items_paginate(int $per_page = 15, $filters=[], string $order='asc', string $column = null): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $items = $this->model->newQuery();

        if ($filters)
        {
            foreach ($filters as $field_name => $filter)
            {

                if ($field_name != 'page') {
                    if ($filter['type'] == 'search') {
                        $items->where($field_name, 'LIKE', '%' . $filter['value'] . '%');
                    } elseif ($filter['type'] == 'filter') {
                        $items->where($field_name, '=', $filter['value']);
                    } elseif ($filter['type'] == 'less') {
                        $items->where($field_name, '<=', $filter['value']);
                    } elseif ($filter['type'] == 'more') {
                        $items->where($field_name, '>=', $filter['value']);
                    }
                }

            }

        }
        $items->orderBy($column ?? $this->model->getKeyName(),$order);
        return $items->paginate($per_page);
    }

    public function delete_item($id){
        return $this->model->find($id)->delete();
    }

}
