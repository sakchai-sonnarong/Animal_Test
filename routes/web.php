<?php

use App\Http\Controllers\AnimalController;
use Illuminate\Support\Facades\Route;


Route::get('/create', [AnimalController::class, 'create'])->name('create');
Route::get('/show',[AnimalController::class, 'index'])->name('show');
Route::post('/insert',[AnimalController::class,'insert'])->name('insert');
Route::get('/delete/{id}',[AnimalController::class, 'delete'])->name('delete');
Route::get('/edit/{id}', [AnimalController::class, 'edit'])->name('edit');
Route::post('/update/{id}', [AnimalController::class, 'update'])->name('update');
