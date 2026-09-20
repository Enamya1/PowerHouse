<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Membership;
use App\Models\MembershipType;
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
    public function update_role_info(Request $request,$id){
        $validation = $request->validate([
            'role_name'=>'required|string|max:255',
        ]);
        $role = Role::find($id);
        if (!$role){
            return response()->json([
                'message'=>'role not found 🙈'
            ],404);
        };
        $role->update($validation);
        return response()->json([
            'message'=>'succeed 👌',
            'role'=>$role
        ],200);

    }

    public function list_all_user_memberships(){
        $memberships = Membership::with(['user:id,name,email', 'membershipType:id,membership_type,price'])->get();
        return response()->json([
            'memberships' => $memberships
        ], 200);
    }

    public function get_membership_by_id($id){
        if (!is_numeric($id) || $id < 1) {
            return response()->json([
                'message' => 'Invalid membership ID'
            ], 422);
        }
        $membership = Membership::with(['user:id,name,email', 'membershipType:id,membership_type,price'])->find($id);
        if (!$membership) {
            return response()->json([
                'message' => 'Membership not found 🙈'
            ], 404);
        }
        return response()->json([
            'membership' => $membership
        ], 200);
    }

    public function create_user_membership(Request $request){
        $validation = $request->validate([
            'user_id'            => 'required|integer|min:1|exists:users,id',
            'membership_type_id' => 'required|integer|min:1|exists:membership_type,id',
            'start_date'         => 'nullable|date',
            'end_date'           => 'nullable|date|after_or_equal:start_date',
            'status'             => 'required|in:active,expired,cancelled,suspended',
            'payment_status'     => 'required|in:paid,pending,failed,refunded',
        ]);
        $membership = Membership::create($validation);
        $membership->load(['user:id,name,email', 'membershipType:id,membership_type,price']);
        return response()->json([
            'message'    => 'succeed 👌',
            'membership' => $membership
        ], 201);
    }

    public function update_user_membership(Request $request, $id){
        if (!is_numeric($id) || $id < 1) {
            return response()->json([
                'message' => 'Invalid membership ID'
            ], 422);
        }
        $validation = $request->validate([
            'user_id'            => 'required|integer|min:1|exists:users,id',
            'membership_type_id' => 'required|integer|min:1|exists:membership_type,id',
            'start_date'         => 'nullable|date',
            'end_date'           => 'nullable|date|after_or_equal:start_date',
            'status'             => 'required|in:active,expired,cancelled,suspended',
            'payment_status'     => 'required|in:paid,pending,failed,refunded',
        ]);
        $membership = Membership::find($id);
        if (!$membership) {
            return response()->json([
                'message' => 'Membership not found 🙈'
            ], 404);
        }
        $membership->update($validation);
        $membership->load(['user:id,name,email', 'membershipType:id,membership_type,price']);
        return response()->json([
            'message'    => 'succeed 👌',
            'membership' => $membership
        ], 200);
    }

    public function delete_user_membership($id){
        if (!is_numeric($id) || $id < 1) {
            return response()->json([
                'message' => 'Invalid membership ID'
            ], 422);
        }
        $membership = Membership::find($id);
        if (!$membership) {
            return response()->json([
                'message' => 'Membership not found 🙈'
            ], 404);
        }
        $membership->delete();
        return response()->json([
            'message' => 'Membership has been deleted 👌'
        ], 200);
    }

    public function toggle_membership_status($id){
        if (!is_numeric($id) || $id < 1) {
            return response()->json([
                'message' => 'Invalid membership ID'
            ], 422);
        }
        $membership = Membership::find($id);
        if (!$membership) {
            return response()->json([
                'message' => 'Membership not found 🙈'
            ], 404);
        }
        $membership->status = ($membership->status === 'suspended') ? 'active' : 'suspended';
        $membership->save();
        return response()->json([
            'message'    => 'Membership status updated 👌',
            'membership' => $membership
        ], 200);
    }

    public function add_membership_type(Request $request){
        $validation = $request->validate([
            'membership_type' => 'required|string|max:20|unique:membership_type,membership_type',
            'price'           => 'required|numeric|min:0|between:0,999999.99',
            'description'     => 'required|string|max:255',
            'duration_days'   => 'required|integer|min:1',
            'status'          => 'required|boolean',
        ]);
        $membership_type = MembershipType::create($validation);
        return response()->json([
            'message'         => 'succeed 👌',
            'membership_type' => $membership_type
        ], 201);
    }
    public function remove_membership_type($id){
        $membership_type = MembershipType::find($id);
        if (!$membership_type){
            return response()->json([
                'message'=>'membership_type not found 🙈'
            ],404);
        };
        $membership_type->delete();
        return response()->json([
            'message'=>'membership_type has been deleted 👌'
        ],200);
    }
    public function update_membership_type(Request $request,$id){
        if (!is_numeric($id) || $id < 1) {
            return response()->json([
                'message' => 'Invalid membership type ID'
            ], 422);
        }
        $validation = $request->validate([
            'membership_type' => 'required|string|max:20|unique:membership_type,membership_type,' . $id,
            'price'           => 'required|numeric|min:0|between:0,999999.99',
            'description'     => 'required|string|max:255',
            'duration_days'   => 'required|integer|min:1',
            'status'          => 'required|boolean',
        ]);
        $membership_type = MembershipType::find($id);
        if (!$membership_type){
            return response()->json([
                'message'=>'membership_type not found 🙈'
            ],404);
        };
        $membership_type->update($validation);
        return response()->json([
            'message'         => 'succeed 👌',
            'membership_type' => $membership_type
        ],200);
    }
    public function get_membership_type_info_by_id($id){
        $membership_type = MembershipType::find($id);
        if (!$membership_type){
            return response()->json([
                'message'=>'membership_type not found 🙈'
            ],404);
        };
        return response()->json([
            $membership_type
        ],200);
    }


    





}