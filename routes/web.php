<?php

use App\Livewire\Dashboard;
use App\Livewire\Placeholder;
use App\Livewire\ListUser;
use App\Livewire\JournalEntry\JournalEntryList;
use App\Livewire\JournalEntry\JournalEntryForm;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::get('/dashboard', Dashboard::class)->name('dashboard');

Route::group(['prefix'=>'user'], function(){
    Route::get('/', ListUser::class)->name('users');
});

// Financial Routes
Route::get('/journal-entries', JournalEntryList::class)->name('journal-entries.index');
Route::get('/journal-entries/create', JournalEntryForm::class)->name('journal-entries.create');
Route::get('/journal-entries/{entry}/edit', JournalEntryForm::class)->name('journal-entries.edit');

Route::get('/coa', App\Livewire\Coa::class)->name('coa');
Route::get('/accounting', Placeholder::class)->name('accounting')->defaults('title', 'Accounting');
Route::get('/banking', Placeholder::class)->name('banking')->defaults('title', 'Banking');
Route::get('/invoices', Placeholder::class)->name('invoices')->defaults('title', 'Invoices');
Route::get('/bills', Placeholder::class)->name('bills')->defaults('title', 'Bills');

// Reports
Route::get('/reports', Placeholder::class)->name('reports')->defaults('title', 'Reports');

// Language Switch Route
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'kh'])) {
        session(['locale' => $locale]);
    }
    return back();
})->name('lang.switch');

// System
Route::get('/settings', Placeholder::class)->name('settings')->defaults('title', 'Settings');