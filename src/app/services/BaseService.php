<?php

namespace App\services;

use Illuminate\Database\Eloquent\Model;

class BaseService
{
    public function create($data, Model $model)
    {
        $data = $this->preCreate($data, $model);
        $createdModel = $model->create($data);
        $this->postCreate($data, $createdModel);
        return $createdModel;
    }

    public function update($data, Model $model)
    {
        $this->preUpdate($data, $model);
        $model->update($data);
        $this->postUpdate($data, $model);
        return $model;
    }

    public function delete(Model $model, $data = [])
    {
        $this->preDelete($data, $model);
        $model->delete();
        $this->postDelete($data, $model);

    }

    protected function preCreate($data, Model $model)
    {
        return $data;
    }

    protected function postCreate($data, Model $model)
    {

    }

    protected function preUpdate($data, Model $model)
    {

    }

    protected function postUpdate($data, Model $model)
    {

    }

    protected function preDelete($data, Model $model)
    {

    }

    protected function postDelete($data, Model $model)
    {

    }


}
