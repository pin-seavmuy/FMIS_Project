<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class BaseComponent extends Component
{
    public string $view;
    protected $service;
    /** @var \Illuminate\Support\Collection|array */
    public $datas = [];

    public function render()
    {
        return view($this->view);
    }
}
