<?php

use App\Http\Controllers\PetController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PetController::class, 'index'])->name('pets.index');
Route::resource('pets', PetController::class)->except(['show']);
Route::get('/pets/create', [PetController::class, 'create'])->name('pets.create');
Route::post('/pets/{petId}/upload-image', [PetController::class, 'uploadImage'])->name('pets.uploadImage');
Route::post('/pets/{petId}/update-with-form', [PetController::class, 'updateWithForm'])->name('pets.updateWithForm');
