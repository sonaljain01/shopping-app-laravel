<?php

use App\Http\Controllers\admin\AdminLoginController;
use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\admin\HomeController;
use App\Http\Middleware\AdminRedirectIfAuthenticated;
use App\Http\Middleware\AdminAuthenticate;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\TempImagesController;


Route::get('/', function () {
    return view('welcome');
});


Route::group(['prefix'=>'admin'], function () {
    Route::middleware(['admin.guest'])->group(function () {
        Route::get('/login', [AdminLoginController::class, 'index'])->name('admin.login');
        Route::post('/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
    });
    Route::middleware(['admin.auth'])->group(function () {
        Route::get('/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
        Route::get('/logout', [HomeController::class, 'logout'])->name('admin.logout');

        //category Routes
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.delete');

        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

        //temp-images.create
        Route::post('/upload-temp-image', [TempImagesController::class, 'create'])->name('temp-images.create');


        Route::get('/getSlug', function(Request $request){
            $slug = ' ';
            if(!empty($request->name)){
                $slug = \Str::slug($request->name);
            }

            return response()->json([
                'status'=> true,
                'slug' => $slug
            ]);

        })->name('getSlug');
    });


});
