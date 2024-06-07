<?php

namespace App\services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BaseService
{
    public function create($data, Model $model, $relationsNeedToLoad = [])
    {

        DB::beginTransaction();
        $data = $this->preCreateOrUpdate($data, $model);
        $data = $this->preCreate($data, $model);
        $createdModel = $model->create($data);
        $createdModel->refresh();
        $createdModel->load($relationsNeedToLoad);
        $this->postCreate($data, $createdModel);
        $this->postCreateOrUpdate($data, $createdModel);
        DB::commit();
        return $createdModel;
    }

    public function update($data, Model $model, $relationsNeedToLoad = [])
    {
        DB::beginTransaction();
        $data = $this->preCreateOrUpdate($data, $model);
        $this->preUpdate($data, $model);
        $model->update($data);
        $model->refresh();
        $model->load($relationsNeedToLoad);
        $this->postUpdate($data, $model);
        $this->postCreateOrUpdate($data, $model);
        DB::commit();
        return $model;
    }

    public function delete(Model $model, $data = [])
    {
        DB::beginTransaction();
        $this->preDelete($data, $model);
        $model->delete();
        $this->postDelete($data, $model);
        DB::commit();
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

    protected function preCreateOrUpdate($data, Model $model)
    {
        return $data;
    }

    protected function postCreateOrUpdate($data, Model $model)
    {
    }


}
