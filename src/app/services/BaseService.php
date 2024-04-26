<?php

namespace App\services;

use Illuminate\Database\Eloquent\Model;

class BaseService
{
    public function create($data, Model $model, $relationsNeedToLoad = [])
    {
        $data = $this->preCreate($data, $model);
        $createdModel = $model->create($data);
        $createdModel->load($relationsNeedToLoad);
        $this->postCreate($data, $createdModel);
        return $createdModel;
    }

    public function update($data, Model $model, $relationsNeedToLoad = [])
    {
        $this->preUpdate($data, $model);
        $model->update($data);
        $model->load($relationsNeedToLoad);
        $this->postUpdate($data, $model);
        return $model;
    }

    public function delete(Model $model, $data = [])
    {
        $this->preDelete($data, $model);
        $model->delete();
        $this->postDelete($data, $model);

    }

    public function getAll(Model $model, $relationsNeedToLoad = [])
    {
        return $model::with($relationsNeedToLoad)->get();
    }

    public function get(Model $model, $relationsNeedToLoad = [])
    {
        return $model->load($relationsNeedToLoad);
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
