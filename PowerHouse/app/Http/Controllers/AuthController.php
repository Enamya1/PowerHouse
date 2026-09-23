<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Membership;
use App\Models\MembershipType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\PaymentRequest;
use PhpParser\Node\Expr\FuncCall;

class AuthController extends Controller
{
    public function sing_up(Request $request){
        // maek the validation 
        $validate = $request->validate([
            'name'=>'required|string|min:2|max:255',
            'email'=>'required|email|max:255|unique:users,email',
            'ID_nO'=>'required|string|max:50|unique:users,ID_nO',
            'password'=>'required|string|min:8|confirmed',
        ]);
        // create the user base on the validated result
        $user = User::create([
            'name'=>$validate['name'],
            'email'=>$validate['email'],
            'ID_nO'=>Hash::make($validate['ID_nO']),
            'password'=> Hash::make($validate['password']),
            'attendance_qr_key'=>Str::random(64),
        ]);

        // return the respond 
        return response()->json([
            'message'=>'👍',
            'user'=>$user,
        ],201);


    }


    public function login(Request $request){
        //make the validation 
        $validate= $request->validate([
                    'email'=>'required|email',
                    'password'=>'required|string|min:8'
        ]);
        // find the user 
        $user =User::where('email',$validate['email'])->first();
        // condition 
        if (!$user || !Hash::check($validate['password'],$user->password)){
            return response()->json([
                'message'=>'wrong email or password ',
                
            ],401);
        }
        if (!$user->is_active){
            return response()->json([
                'message'=>'your account is not active yet ❌'
            ],403);
        }
        //  generate the token 
        $token= $user->createToken("web_app")->plainTextToken;
        // retunr the finale resulte 
        return response()->json([
            'message'=>'👍',
            'user'=>$user,
            'Token'=>$token,
            'role'=>$user->role_name
        ],200);
        
    }
    public function log_out(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message'=>'Logged out successfully ✅'
        ],200);
    }
    public function change_password(Request $request){
        $user = $request->user();
        $validation = $request->validate([
            "new_password"=>'required|string|min:8|confirmed'
        ]);
        if (Hash::check($validation['new_password'], $user->password)) {
            return response()->json([
                'message'=>'new password is the same as old password ❌'
            ],400);
        }
        $user->update([
            'password' => $validation['new_password']
        ]);
        return response()->json([
            'message'=>'password updated successfully'
        ],200);
    }
   

    






  












    // public function sing_up(Request $request){  
    //       $validate =$request->validate([
    //             'name'=>'required|string|max:255',
    //             'email'=>'required|email|unique:users,email',
    //             'ID_nO'=>'required|string|max:255',
    //             'password' => [
    //                 'required',
    //                 'confirmed',
    //                 Password::min(8),
    //                 ]

    //         ]);
    //         $user =User ::create([
    //             'name'=>$validate['name'],
    //             'email'=>$validate['email'],
    //             'ID_nO'=>$validate['ID_nO'],
    //             'password'=>Hash::make($validate['password'])
    //         ]);

    //         return response()->json([
    //             'message'=>'good boooooy ',
    //             'user'=>$user,
    //         ],201);
    // }


    // public function login(Request $request){
    //     // validation
    //     $validate = $request->validate([
    //         'email'=>'required|email',
    //         'password'=>'required|string',
    //     ]);
    //     // find user by email 
    //     $user =User::where('email',$validate['email'])->first();
        
    //     // conditions for unmatching resulte 

    //     if (!$user || !Hash::check($validate['password'],$user->password)) {
    //         return response()->json([
    //             'message'=>'invalid password ',
    //         ],401);
    //     };
    //     // create aut token 
    //     $token = $user->createToken('web_app')->plainTextToken;
    //     // return final response 
    //     return response()->json([
    //         'message'=>'login successful',
    //         'user'=>$user,
    //         'token'=>$token,
    //     ],200);

    // }
}
