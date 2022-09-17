<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;

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

    Route::prefix('/voucher')->group(function(){
        Route::get('/',[VoucherController::class, 'list']);
        Route::get('/form',[VoucherController::class, 'form']);
        Route::post('/form',[VoucherController::class, 'save']);
        Route::get('/delete',[VoucherController::class, 'delete']);
    });

    Route::prefix('/trx')->group(function(){
        Route::get('/',[TransactionController::class, 'list']);
        Route::get('/create',[TransactionController::class, 'create']);
        Route::post('/create',[TransactionController::class, 'createTrx']);
        Route::get('/update/{id}',[TransactionController::class, 'update']);
        Route::post('/update/{id}',[TransactionController::class, 'updateTrx']);
        Route::get('/paid',[TransactionController::class, 'setPaid']);
        Route::get('/cancel',[TransactionController::class, 'setCancel']);
        Route::get('/delete',[TransactionController::class, 'delete']);
        Route::get('/product/{id}',[TransactionController::class, 'getProduct']);
        Route::post('/voucher',[TransactionController::class, 'claimVoucher']);
    });

    Route::prefix('/authorization')->group(function(){
        Route::prefix('/role')->group(function(){
            Route::get('/',[RoleController::class, 'list']);
            Route::get('/form',[RoleController::class, 'form']);
            Route::post('/form',[RoleController::class, 'save']);
        });

        Route::prefix('/user')->group(function(){
            Route::get('/',[UserController::class, 'list']);
        });
    });
});
