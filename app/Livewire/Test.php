<?php

namespace App\Livewire;

use App\Models\User;
use App\Services\UserService;
use App\Traits\IncomeTrait;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Test extends BaseComponent
{
    use IncomeTrait;
    public $user = [];
    public function mount(UserService $service)
    {
        $this->datas = $this->UserReport($service);
    }

    public function createUser(UserService $service){
        $service->create($this->user);
        $this->datas = $this->UserReport($service);
    }

    public function render()
    {
        return view('livewire.test');
    }
    
}
