<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{


     public function index () {
        $users=User::all('*');
        return response()->json($users);
    }
    public function store(Request $request){
        $user = user::create([
            'name'=>$request->name,
            'password'=>$request->password,
            'email'=>$request->email,
            'ID_nO'=>$request->ID_nO

        ]);
        return response()->json($user,201);
    }

}
