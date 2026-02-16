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
}