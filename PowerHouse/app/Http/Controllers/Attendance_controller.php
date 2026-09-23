<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use App\Models\Attendance;

class Attendance_controller extends Controller
{
    public function check_in_out(Request $request)
    {
        // VALIDATE QR KEY
        $validation = $request->validate([
            'qr_key' => [
                'required',
                'string',
                'size:64',
            ],
        ]);

        // FIND USER BY QR KEY
        $qrUser = User::where(
            'attendance_qr_key',
            $validation['qr_key']
        )->first();

        // QR KEY NOT FOUND
        if (!$qrUser) {
            return response()->json([
                'message' => 'QR key not found ❌'
            ], 404);
        }

        // GET AUTHENTICATED USER
        $user = $request->user();

        // MAKE SURE THE QR BELONGS TO THE LOGGED-IN USER
        if ($qrUser->id != $user->id) {
            return response()->json([
                'message' => 'This QR code belongs to another user ❌'
            ], 403);
        }

        // FIND CURRENT ATTENDANCE
        $attendance = Attendance::where(
            'user_id',
            $user->id
        )
            ->where(
                'attendance_status',
                'in'
            )
            ->first();

        // CHECK-OUT
        if ($attendance) {

            $attendance->check_out = now();
            $attendance->attendance_status = 'out';
            $attendance->save();

            return response()->json([
                'message' => 'Check-out successful ✅',
                'qr_key' => $user->attendance_qr_key,
                'user_id' => $user->id,
                'check_in' => $attendance->check_in,
                'check_out' => $attendance->check_out,
                'status' => $attendance->attendance_status,
            ], 200);
        }

        // CHECK-IN
        $attendance = Attendance::create([
            'user_id' => $user->id,
            'check_in' => now(),
            'check_out' => null,
            'attendance_status' => 'in',
        ]);

        return response()->json([
            'message' => 'Check-in successful ✅',
            'qr_key' => $user->attendance_qr_key,
            'user_id' => $user->id,
            'check_in' => $attendance->check_in,
            'check_out' => $attendance->check_out,
            'status' => $attendance->attendance_status,
        ], 200);
    }








    public function get_attendance_record(Request $request)
    {
        $user = $request->user();
        $attendance = Attendance::where(
            'user_id',
            $user->id
        )
            ->get();
        return response()->json([
            'message' => 'Attendance retrieved successfully ✅',
            'attendance' => $attendance,
        ], 200);
    }






    public function get_current_attendance(Request $request)
    {
        $user = $request->user();
        $attendance = Attendance::where(
            'user_id',
            $user->id
        )
            ->where(
                'attendance_status',
                'in'
            )
            ->first();
        if (!$attendance) {
            return response()->json([
                'message' => 'user is out rescan the qr code for entry'
            ], 404);
        }
        if ($attendance->attendance_status == 'out') {
            return response()->json([
                'message' => 'Current attendance is not in ✅',
                'time' => $attendance->check_in,
            ], 200);
        }
        if ($attendance->attendance_status == 'in') {
            return response()->json([
                'message' => 'Current attendance is in ✅',
                'time' => $attendance->check_in,
            ], 200);
        }
    }



    public function get_attendace_qr_key(Request $request)
    {
        $user = $request->user();
        if (!$user->attendance_qr_key) {
            return response()->json([
                'message' => 'QR key not found ❌'
            ], 404);
        }
        return response()->json([
            'message' => 'Attendance QR key retrieved successfully ✅',
            'qr_key' => $user->attendance_qr_key,
        ], 200);
    }
}
