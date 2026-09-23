<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\User;



class ModeratorController extends Controller
{
    public function get_all_users(Request $request){
        $users = User::all();
        return response()->json([
            'message'=>'success',
            'users'=>$users
        ],200);
    }
}
