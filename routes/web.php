<?php

use App\Livewire\Dashboard;
use App\Livewire\Test;
use Illuminate\Support\Facades\Route;

Route::get('/', Dashboard::class)->name('home');

Route::group(['prefix'=>'test'], function($route){
    Route::livewire('/', Test::class);
});
