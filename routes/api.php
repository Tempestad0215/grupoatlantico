<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




Route::controller(UserController::class)
    ->prefix("user")
    ->group(function () {
        Route::post("/", "store");
        Route::get("/{user}","show");
        Route::put("/{user}","update");
    });
