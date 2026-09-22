<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MembershipType;
use App\Models\Membership;
use App\Models\PaymentRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserController extends Controller
{

     public function get_profile_info(Request $request){
        $user = $request->user();
        return response()->json([
            'user'=>$user
        ],200);
    }
   

     public function delete_acount($confirmation){
        if ($confirmation!=='delete'){
            return response()->json([
                'message'=>'wrong input ❌'
            ],400);
        };
        $user=request()->user();
        $user->delete();
        return response()->json([
            'message'=>'acount has been deleted 👍'
        ],200);

    }
    public function sing_up_for_membership(Request $request, $membership_type_id){
    $user = $request->user();

    // Check authentication
    if (!$user) {
        return response()->json([
            'message' => 'User not authenticated ❌'
        ], 401);
    }

    // Find membership type
    $membershipType = MembershipType::find($membership_type_id);

    if (!$membershipType) {
        return response()->json([
            'message' => 'Membership type not found ❌'
        ], 404);
    }

    // Check existing membership
    $existingMembership = $user->memberships()
        ->where('membership_type_id', $membershipType->id)
        ->whereIn('status', ['active', 'pending'])
        ->first();

    if ($existingMembership) {
        return response()->json([
            'message' => 'You already have this membership ❌'
        ], 400);
    }

    // Calculate membership dates
    $startDate = now();
    $endDate = now()->addDays($membershipType->duration_days);

    // FREE MEMBERSHIP
    
    if ($membershipType->price == 0) {

        $membership = $user->memberships()->create([
            'membership_type_id' => $membershipType->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 'active',
            'payment_status' => 'paid',
        ]);

        return response()->json([
            'message' => 'Membership activated successfully 👌',
            'membership' => $membership,
        ], 201);
    }

    // PAID MEMBERSHIP
    $membership = $user->memberships()->create([
        'membership_type_id' => $membershipType->id,
        'start_date' => $startDate,
        'end_date' => $endDate,
        'status' => 'pending',
        'payment_status' => 'pending',
    ]);

    // Create payment request
    $paymentRequest = PaymentRequest::create([
        'user_id' => $user->id,
        'membership_type_id' => $membershipType->id,
        'amount' => $membershipType->price,
        'qr_key' => Str::random(64),
        'status' => 'pending',
        'expires_at' => now()->addDays(7),
    ]);

    return response()->json([
        'message' => 'Membership created. Payment required 💳',
        'membership' => $membership,
        'paymentRequest' => $paymentRequest,
    ], 201);
}

    public function get_payment_requests(Request $request){
        $user = $request->user();
        $paymentRequests = PaymentRequest::where('user_id',$user->id)->get();        
        return response()->json([
            'paymentRequests'=>$paymentRequests
        ],200);
    }
   public function get_payment_qr_code(Request $request)
{
    $user = $request->user();
    $pay_reqest= PaymentRequest::where('user_id',$user->id)->first();
    $qr_key= $pay_reqest->qr_key;
    if (!$qr_key) {
        return response()->json([
            'message' => 'Payment request not found ❌'
        ], 404);
    }
    return response()->json([
        'qr_code' => $qr_key
    ], 200);
}
    public function update_request(Request $request) { 
        $user = $request->user();
        $payment_request= PaymentRequest::where('user_id',$user->id)->first();
        if (!$payment_request) {
            return response()->json([
                'message' => 'Payment request not found ❌'
            ], 404);
        }
        $validation = $request->validate([
            'membership_type_id' => 'required|integer|exists:membership_types,id',
        ]);
        $payment_request->update([
            'membership_type_id' => $validation['membership_type_id'],
        ]);
       
        return response()->json([
            'message' => 'Payment request updated successfully 👌',
            'request'=>$payment_request
        ], 200);
    }
}
