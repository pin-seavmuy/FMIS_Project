<?php

use App\Livewire\Dashboard;
use App\Livewire\Placeholder;
use App\Livewire\ListUser;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::get('/dashboard', Dashboard::class)->name('dashboard');

Route::group(['prefix'=>'test'], function(){
    Route::get('/', ListUser::class)->name('users');
});

// Financial Routes
Route::get('/accounting', Placeholder::class)->name('accounting')->defaults('title', 'Accounting');
Route::get('/banking', Placeholder::class)->name('banking')->defaults('title', 'Banking');
Route::get('/invoices', Placeholder::class)->name('invoices')->defaults('title', 'Invoices');
Route::get('/bills', Placeholder::class)->name('bills')->defaults('title', 'Bills');

// Reports
Route::get('/reports', Placeholder::class)->name('reports')->defaults('title', 'Reports');

// System
Route::get('/settings', Placeholder::class)->name('settings')->defaults('title', 'Settings');