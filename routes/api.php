<?php

use App\Http\Controllers\admin\AdminOrderController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register']);
   Route::get('/printers', [AdminOrderController::class, 'getPrinters']);
});

