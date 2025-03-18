<?php

use App\Http\Controllers\PetController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PetController::class, 'index'])->name('pets.index');
Route::resource('pets', PetController::class)->except(['show']);
Route::get('/pets/create', [PetController::class, 'create'])->name('pets.create');
Route::post('/pets', [PetController::class, 'store'])->name('pets.store');
