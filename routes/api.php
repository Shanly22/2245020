<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

route::group(['prefix'=>'v1'], function(){
    // api/v1/users
    Route::post('/users',[App\Http\Controllers\UserController::class,'register']);
    Route::post('/users/login',[App\Http\Controllers\UserController::class,'login']);

    Route::middleware(App\Http\Middleware\ApiAuthMiddleware::class)->group(function (){
        Route::get('/users/profile',[App\Http\Controllers\UserController::class,'get']);
        route::patch('/users/profile',[App\Http\Controllers\UserController::class,'update']);
    });
});
