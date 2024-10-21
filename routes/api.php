<?php

use App\Http\Controllers\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [RegisterController::class, 'register']);
   
});

Route::get('test', function () {
    return response()->json(['message' => 'API routes are working']);
});