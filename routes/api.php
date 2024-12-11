<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PartyanimalControler;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//animal 
Route::get('/getanimal', [PartyanimalControler::class, 'getanimal']);
Route::post('/insert', [PartyanimalControler::class, 'insert']);
Route::get('/delete/{id}', [PartyanimalControler::class, 'delete']);
Route::post('/update/{id}', [PartyanimalControler::class, 'update']);
Route::get('/getanimalid/{id}', [PartyanimalControler::class, 'getanimalid']);
Route::post('/insertmore', [PartyanimalControler::class, 'insertmore']);
Route::put('/updatemore', [PartyanimalControler::class, 'updatemore']);
Route::get('/searchname', [PartyanimalControler::class, 'searchByName']);
Route::get('/outputPDF/{id}', [PartyanimalControler::class, 'exportPDF']);
Route::get('/allanimalPDF', [PartyanimalControler::class, 'exportPDFAll']);

//type animal
Route::post('/inserttype',[PartyanimalControler::class, 'inserttype']);
Route::get('/searchbytype',[PartyanimalControler::class, 'searchbytype']);