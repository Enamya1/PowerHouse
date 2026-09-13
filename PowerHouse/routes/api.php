<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\AdminController;

// test 
Route::get('/test', function () {
    return response()->json([
        'message' => 'API is working!'
    ]);
});




Route::get('/test_v2', function (Request $request) {
    dd($request->user());
});


// Route::get('/users',[UserController::class,'index']);
// Route::POST('/user',[UserController::class,'store']);




Route::post('/sing_up',[AuthController::class,'sing_up']);
Route::post('/login',[AuthController::class,'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'log_out']);
    Route::get('/profile',[AuthController::class,'get_profile_info']);
    Route::put('/change_password',[AuthController::class,'change_password']);
    Route::DELETE('/delete/{confirmation}',[AuthController::class,'delete_acount']);
});


Route::middleware(['auth:sanctum', 'admin'])
    ->prefix('admin')
    ->group(function () {

        // Get all users
        Route::get('/users', [AdminController::class, 'get_all_user']);

        // Get one user by ID
        Route::get('/users/{id}', [AdminController::class, 'get_user_by_id']);
        // delet one user by id 
        Route::delete('/delete/{id}', [AdminController::class, 'delete_user_by_id']);
        Route::patch('/change_role', [AdminController::class, 'update_user_role']);
        Route::patch('/update/{id}', [AdminController::class, 'updaet_user_info']);

    });