<?php

namespace App\Http\Controllers;

use App\Models\Role;
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

// function to updaet the role of the user 
    public function update_user_role(Request $request){
        $validation = $request->validate([
            'user_id'=>'required|integer|min:1',
            'new_role'=>'required|string|in:admin,user,moderator'
        ]);
       $user =User::find($validation['user_id']);
       $role_id = $validation['new_role'];
        // error checck 
        if (!$user){
            return response()->json([
                'message'=>'user do not exicit (your are imaging stuff are you sure u are sober 🥴 )',
            ],404);
        };
    //    check for the role 
        if ($role_id=='admin'){
            $user->update([
                'role_id'=>1
            ]);
            return response()->json([
                'message'=>'succeed 👌',
                'user'=>$user
            ],200);
        }
        elseif ($role_id=='user'){
            $user->update([
                'role_id'=>2
            ]);
            return response()->json([
                'message'=>'succeed👌',
                'user'=>$user
            ],200);
        }
        elseif ($role_id=='moderator'){
            $user->update([
                'role_id'=>3
            ]);
            return response()->json([
                'message'=>'succeed👌',
                'user'=>$user
            ],200);
        };
    }
    public function updaet_user_info(Request $request,$id){
        if (!$id || $id < 1){
            return response()->json([
                'message'=>'invalide id ❌'
            ],422);
        };
        $validation = $request->validate([
            'name' => 'string|min:2|max:255',
            'email' => 'email|max:255',
            'ID_nO' => 'string|max:50',
        ]);
        $user= User::find($id);
        if (!$user) {
            return response()->json([
                'message'=>'user not found 🙈'
            ],404);
        }

        // $user->update($validation);
        $user->fill($validation);
        if ($user->name === $validation['name'] &&
            $user->email === $validation['email'] &&
            $user->ID_nO === $validation['ID_nO']) {
            return response()->json([
                'message'=>'nothing to update '
            ],400);
        }
        $user->save();
        return response()->json([
            'message'=>'👌',
            "user"=>$user
        ],200);
        
        
    }
    public function update_user_status($id){
        $user= User::find($id);
        if (!$user){
            return response()->json([
                'message'=>'user not found 🙈'
            ],404);
        };
        $user->is_active = !$user->is_active;
        $user->save();
        return response()->json([
            'message'=>'succeed 👌',
            'user'=>$user
        ],200);
    }

    public function list_all_roles(){
        $roles = Role::all(['*']);
        return response()->json([
            $roles
        ],200);
    }


    public function get_role_info_by_id($id){
        $role = Role::find($id);
        if (!$role){
            return response()->json([
                'message'=>'role not found 🙈'
            ],404);
        };
        return response()->json([
            $role
        ],200);
    }


    public function create_role(Request $request){
        $validation = $request->validate([
            'role_name'=>'required|string|max:255',
        ]);
        $role = Role::create($validation);
        return response()->json([
            'message'=>'succeed 👌',
            'role'=>$role
        ],200);
    }

    public function delete_role_by_id($id){
        $role = Role::find($id);
        if (!$role){
            return response()->json([
                'message'=>'role not found 🙈'
            ],404);
        };
        $role->delete();
        return response()->json([
            'message'=>'role has been deleted 👌'
        ],200);
    }


}