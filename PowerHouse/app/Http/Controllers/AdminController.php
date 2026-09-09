<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function get_all_user(){
        $user = User::all(['*']);
        return response()->json([
            $user
        ],201);
    }
    public function get_user_by_id($id){
        // check if the id is valid 
         if (!is_numeric($id) || $id < 1) {
        return response()->json([
            'message' => 'Invalid user ID'
        ], 422); 
    }
        
        $user=User::find($id);
        // $user=User::where('id',$validation['id']);

        // check if the user exist 
        
        if (!$user ){
            return response()->json([
                'message'=>'user is not found ❌'
            ],404);
        };

        // final resulte 
        return response()->json([$user],200);
    }
    public function delete_user_by_id($id){
        if (!is_numeric($id) || $id <1 ){
            return response()->json([
                'message'=>'invalid id ❌'
            ],422);
        };
        $user=User::find($id);
        if (!$user){
            return response()->json([
                'message'=>'no user with that id 🙈',
            ],404);
        };
        $user->delete();
        return response()->json([
            'message'=>'user has been deleted 👌'
        ],200);
    }

    // public function change_role(Request $request) {
    //     $validation = $request->validate([
    //         "user_id"=>'required|integer|min:1',
    //         "role_id"=>'required|integer|min:1'
    //     ]);
    //     $user = User::find(validator(['user_id']));
        


        
    // }
}