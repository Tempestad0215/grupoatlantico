<?php

use App\Http\Controllers\BranchesController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;




// Usuarios
Route::controller(UserController::class)
    ->prefix("user")
    ->group(function () {
        Route::post("/", "store");
        Route::get("/","show");
        Route::get("/{user}","edit");
        Route::put("/change-password","changePassword");
        Route::put("/{user}","update");
        Route::post("/login","login");
        Route::post("/verify","verifyEmail");
        Route::post("/forget-password","forgetPassword");
        Route::post("/log-out","logOut");
    });

// Categoria de producto
Route::controller(BranchesController::class)
    ->prefix("branch")
    ->group(function () {
        Route::post("/","store");
        Route::get("/","show");
        Route::get("/{branch}","edit");
        Route::put("/{branch}","update");
        Route::put("/delete/{branch}","destroy");
    });
