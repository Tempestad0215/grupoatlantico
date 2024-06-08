<?php

use App\Http\Controllers\BranchesController;
use App\Http\Controllers\DeparmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EntranceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;




// Usuarios
Route::controller(UserController::class)
    ->prefix('user')
    ->group(function () {
        Route::post('/', 'store');
        Route::get('/','show');
        Route::get('/{user}','edit');
        Route::put('/change-password','changePassword');
        Route::put('/{user}','update');
        Route::post('/login','login');
        Route::post('/verify','verifyEmail');
        Route::post('/forget-password','forgetPassword');
        Route::post('/log-out','logOut');
    });

// Categoria de producto
Route::controller(BranchesController::class)
    ->prefix('branch')
    ->group(function () {
        Route::post('/','store');
        Route::get('/','show');
        Route::get('/{branch}','edit');
        Route::put('/{branch}','update');
        Route::put('/delete/{branch}','destroy');
    });


// Departamento
Route::controller(DeparmentController::class)
    ->prefix('deparment')
    ->group(function () {
        Route::post('/','store');
        Route::get('/','show');
        Route::put('/{deparment}','update');
        Route::get('/{deparment}','edit');
        Route::put('/delete/{deparment}','destroy');
    });


// Empleado
Route::controller(EmployeeController::class)
    ->prefix('employee')
    ->group(function () {
        Route::post('/','store');
        Route::get('/','show');
        Route::get('/{employee}','edit');
        Route::put('/{employee}','update');
    });

// Productos
Route::controller(ProductController::class)
    ->prefix('product')
    ->group(function () {
        Route::post('/','store');
        Route::get('/','show');
        Route::get('/{product}','edit');
        Route::put('/{product}','update');
        Route::put('/delete/{product}','destroy');
    });


// Entrada de producto
Route::controller(EntranceController::class)
    ->prefix('entrance')
    ->group(function () {
        Route::post('/','store');
        Route::get('/','show');
        Route::get('/{entrance}','edit');
        Route::put('/{entrance}','update');
        Route::put('/delete/{entrance}','destroy');
    });
