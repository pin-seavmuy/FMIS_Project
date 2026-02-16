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

    public function create($data){
        $data['password'] = Hash::make($data['password']);
        $this->model->create($data);
    }
}