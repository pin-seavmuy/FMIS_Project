<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseService {
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function list(){
        return $this->model->all();
    }

    public function create(array $data){
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        return $this->model->create($data);
    }

    public function update($id, array $data){
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']); // Keep existing password if empty
        }
        return parent::update($id, $data);
    }
}