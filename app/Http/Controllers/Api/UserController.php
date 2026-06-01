<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user_id = $request->user_id;
        if (!$user_id) {
            return response()->json(['success' => false, 'message' => 'User not authenticated'], 404);
        }

        $user = User::with('prahari')->find($user_id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User profile not found'], 404);
        }

        $prahari = $user->prahari;

        if (!$prahari) {
            return response()->json([
                'success' => false,
                'message' => 'Prahari details not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'name' => $prahari->name,
                'prahari_id' => $prahari->prahari_id,
                'mobile' => $prahari->mobile,
                'email' => $user->email,
                'bank_account' => $prahari->bank_account,
                'aadhaar_status' => $prahari->aadhaar_status,
                'status' => $prahari->status,
                'wallet_balance' => $prahari->wallet_balance,
            ]
        ]);
    }

    /**
     * Update user's personal details (Profile).
     */
    public function updateProfile(Request $request)
    {
        $user_id = $request->user_id;
        if (!$user_id) {
            return response()->json(['success' => false, 'message' => 'User not authenticated'], 404);
        }

        $user = User::with('prahari')->find($user_id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User profile not found'], 404);
        }

        $prahari = $user->prahari;
        if (!$prahari) {
            return response()->json(['success' => false, 'message' => 'Prahari details not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:15',
            'bank_account' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $prahari->update([
            'name' => $request->name,
            'mobile' => $request->mobile,
            'bank_account' => $request->bank_account,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully'
        ]);
    }

    /**
     * Change user's password.
     */
    public function changePassword(Request $request)
    {
        $user_id = $request->user_id;
        if (!$user_id) {
            return response()->json(['success' => false, 'message' => 'User not authenticated'], 404);
        }

        $user = User::find($user_id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User profile not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Old password does not match'
            ], 400);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully'
        ]);
    }
}
