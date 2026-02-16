<?php

use App\Livewire\Dashboard;
use App\Livewire\Test;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::get('/dashboard', Dashboard::class)->name('dashboard');

Route::group(['prefix'=>'test'], function(){
    Route::get('/', Test::class);
});
