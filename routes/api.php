<?php

use App\Http\Controllers\Api\SortController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/sort/covers-list',[SortController::class, 'sortCoversList'])
    ->name('api.covers-list');
