<?php

use Illuminate\Support\Facades\Route;

Route::get('/', \App\Livewire\ShowHome::class)->name('home');
Route::get('/services',\App\Livewire\ShowServicesPage::class)->name('servicePage');
Route::get('/service/{id}',\App\Livewire\ServiceDetail::class)->name('serviceDetail');
Route::get('/team',\App\Livewire\ShowTeam::class)->name('showTeam');
