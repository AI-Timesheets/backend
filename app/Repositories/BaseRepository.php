<?php

namespace App\Repositories;

class BaseRepository {
    private $relations = [];
    private $model;

    protected function __construct($model, $relations) {
        $this->model = $model;
        $this->relations = $relations;
    }

    public function getAll() {
        return $this->model->with($this->relations)->get();
    }

    public function getOne($id) {
        $record = $this->model->where("id", $id)->with($this->relations);

        if (!$record) {
            throw new \Exception("Record does not exist");
        }
    }
}
