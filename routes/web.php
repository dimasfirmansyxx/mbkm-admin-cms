<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissionController;

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

Route::middleware(['auth','permission'])->group(function(){
    Route::get('/',function(){
        return view('dashboard');
    });

    Route::prefix('/product')->group(function(){
        Route::get('/',[ProductController::class, 'productList'])->name('product|view');
        Route::get('/form',[ProductController::class, 'productForm'])->name('product|add');
        Route::get('/form/{id}',[ProductController::class, 'productForm'])->name('product|edit');
        Route::post('/form',[ProductController::class, 'productSave'])->name('product|add');
        Route::post('/form/{id}',[ProductController::class, 'productSave'])->name('product|edit');
        Route::get('/delete',[ProductController::class, 'productDelete'])->name('product|delete');

        Route::prefix('/category')->group(function(){
            Route::get('/',[ProductController::class, 'categoryList'])->name('product_category|view');
            Route::get('/form',[ProductController::class, 'categoryForm'])->name('product_category|add');
            Route::get('/form/{id}',[ProductController::class, 'categoryForm'])->name('product_category|edit');
            Route::post('/form',[ProductController::class, 'categorySave'])->name('product_category|add');
            Route::post('/form/{id}',[ProductController::class, 'categorySave'])->name('product_category|edit');
            Route::get('/delete',[ProductController::class, 'categoryDelete'])->name('product_category|delete');
        });
    });

    Route::prefix('/voucher')->group(function(){
        Route::get('/',[VoucherController::class, 'list'])->name('voucher|view');
        Route::get('/form',[VoucherController::class, 'form'])->name('voucher|add');
        Route::get('/form/{id}',[VoucherController::class, 'form'])->name('voucher|edit');
        Route::post('/form',[VoucherController::class, 'save']->name('voucher|add'));
        Route::post('/form/{id}',[VoucherController::class, 'save']->name('voucher|edit'));
        Route::get('/delete',[VoucherController::class, 'delete'])->name('voucher|delete');
    });

    Route::prefix('/trx')->group(function(){
        Route::get('/',[TransactionController::class, 'list'])->name('transaction|view');
        Route::get('/create',[TransactionController::class, 'create'])->name('transaction|add');
        Route::post('/create',[TransactionController::class, 'createTrx'])->name('transaction|add');
        Route::get('/update/{id}',[TransactionController::class, 'update'])->name('transaction|edit');
        Route::post('/update/{id}',[TransactionController::class, 'updateTrx'])->name('transaction|edit');
        Route::get('/paid',[TransactionController::class, 'setPaid'])->name('transaction|edit');
        Route::get('/cancel',[TransactionController::class, 'setCancel'])->name('transaction|edit');
        Route::get('/delete',[TransactionController::class, 'delete'])->name('transaction|delete');
        Route::get('/product/{id}',[TransactionController::class, 'getProduct']);
        Route::post('/voucher',[TransactionController::class, 'claimVoucher']);
    });

    Route::prefix('/authorization')->group(function(){
        Route::prefix('/role')->group(function(){
            Route::get('/',[RoleController::class, 'list'])->name('role|view');
            Route::get('/form',[RoleController::class, 'form'])->name('role|add');
            Route::get('/form',[RoleController::class, 'form'])->name('role|edit');
            Route::post('/form',[RoleController::class, 'save'])->name('role|add');
            Route::post('/form',[RoleController::class, 'save'])->name('role|edit');
            Route::get('/delete',[RoleController::class, 'delete'])->name('role|delete');
        });

        Route::prefix('/user')->group(function(){
            Route::get('/',[UserController::class, 'list'])->name('user|view');
            Route::get('/form',[UserController::class, 'form'])->name('user|add');
            Route::get('/form',[UserController::class, 'form'])->name('user|edit');
            Route::post('/form',[UserController::class, 'save'])->name('user|add');
            Route::post('/form',[UserController::class, 'save'])->name('user|edit');
            Route::get('/delete',[UserController::class, 'delete'])->name('user|delete');
        });

        Route::prefix('/permission')->group(function(){
            Route::get('/',[PermissionController::class, 'list'])->name('user|view');
            Route::post('/',[PermissionController::class, 'save'])->name('user|edit');
        });
    });
});
