<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('auth')->group(function(){
    Route::get('/login',function(){
        return view('login');
    })->name('login');
    Route::post('/login',[AuthController::class, 'login']);

    Route::get('/logout',[AuthController::class, 'logout'])->middleware('auth');
});

Route::middleware('auth')->group(function(){
    Route::get('/',function(){
        return view('dashboard');
    });

    Route::prefix('/product')->group(function(){
        Route::get('/',[ProductController::class, 'productList']);
        Route::get('/form',[ProductController::class, 'productForm']);
        Route::post('/form',[ProductController::class, 'productSave']);
        Route::get('/delete',[ProductController::class, 'productDelete']);

        Route::prefix('/category')->group(function(){
            Route::get('/',[ProductController::class, 'categoryList']);
            Route::get('/form',[ProductController::class, 'categoryForm']);
            Route::post('/form',[ProductController::class, 'categorySave']);
            Route::get('/delete',[ProductController::class, 'categoryDelete']);
        });
    });
});
