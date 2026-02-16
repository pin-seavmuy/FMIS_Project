<?php
namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class BaseService {
    protected Model $model;

    public function __construct(Model $model) {
        $this->model = $model;
    }

    public function create(array $data){
        return $this->model->create($data);
    }

    public function update($id, array $data){
        $record = $this->model->find($id);
        if ($record) {
            $record->update($data);
        }
        return $record;
    }

    public function find($id){
        return $this->model->find($id);
    }

    public function delete($id){
        return $this->model->destroy($id);
    }
}