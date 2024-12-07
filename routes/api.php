<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\MediaController;

/*Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');*/

Route::apiResource('medias', MediaController::class);
Route::apiResource('clients', ClientController::class);
Route::patch('medias/restore/{id}', [MediaController::class, 'restore'])->name('medias.restore');
Route::delete('medias/force/{id}', [MediaController::class, 'forceDelete'])->name('medias.forceDelete');