<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




Route::controller(UserController::class)
    ->prefix("user")
    ->group(function () {
        Route::post("/", "store");
        Route::get("/{user}","show");
        Route::put("/change-password","changePassword");
        Route::put("/{user}","update");
        Route::post("/login","login");
        Route::post("/verify","verifyEmail");
        Route::post("/forget-password","forgetPassword");
        Route::post("/log-out","logOut");
    });
