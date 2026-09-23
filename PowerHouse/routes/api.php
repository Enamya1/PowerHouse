<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Attendance_controller;
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
    Route::get('/profile',[UserController::class,'get_profile_info']);
    Route::put('/change_password',[AuthController::class,'change_password']);
    Route::DELETE('/delete/{confirmation}',[UserController::class,'delete_acount']);
    // sing up for membership and make a payment request
    Route::post('/sing_up_for_membership/{membership_type_id}',[UserController::class,'sing_up_for_membership']);
    //get qr code for each user 
    Route::get('/payment_qr_code',[UserController::class,'get_payment_qr_code']);
    //get payment reqest_info 
    Route::get('/payment_requests',[UserController::class,'get_payment_requests']);
    //update payment request
    Route::put('/update_payment_request',[UserController::class,'update_payment_request']);
    // delete user payment request 
    Route::delete('/delete_payment_request',[UserController::class,'delete_payment_request']);




     // check in or out
    Route::post('/check_in_out',[Attendance_controller::class,'check_in_out']);
    // get attendance record
    Route::get('/attendance_record/my_record',[Attendance_controller::class,'get_attendance_record']);
    // get current attendance
    Route::get('/attendance_record/current',[Attendance_controller::class,'get_current_attendance']);
    // get attendance qr key
    Route::get('/attendance_qr_key/my_qr_key',[Attendance_controller::class,'get_attendace_qr_key']);
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
        Route::patch('/change_status/{id}', [AdminController::class, 'update_user_status']);
        Route::get('/roles', [AdminController::class, 'list_all_roles']);
        Route::get('/roles/{id}', [AdminController::class, 'get_role_info_by_id']);
        Route::post('/make/role', [AdminController::class, 'create_role']);
        Route::delete('/delete/role/{id}', [AdminController::class, 'delete_role_by_id']);
        Route::put('/update/role/{id}', [AdminController::class, 'update_role_info']);
        // list all memberships
        Route::get('/memberships', [AdminController::class, 'list_all_user_memberships']);
        // get membership by id
        Route::get('/memberships/{id}', [AdminController::class, 'get_membership_by_id']);
        // create new user membership
        Route::post('/memberships', [AdminController::class, 'create_user_membership']);
        // update user membership
        Route::put('/memberships/{id}', [AdminController::class, 'update_user_membership']);
        // delete user membership
        Route::delete('/memberships/{id}', [AdminController::class, 'delete_user_membership']);
        // toggle membership status (active/suspended)
        Route::patch('/memberships/{id}/toggle_status', [AdminController::class, 'toggle_membership_status']);
        // add membership type
        Route::post('/add_membership_type',[AdminController::class,'add_membership_type']);
        // remove membership type
        Route::delete('/remove_membership_type/{id}',[AdminController::class,'remove_membership_type']);
        // update membership type
        Route::put('/update_membership_type/{id}',[AdminController::class,'update_membership_type']);
        // get membership type info by id
        Route::get('/membership_type/{id}',[AdminController::class,'get_membership_type_info_by_id']);

    });