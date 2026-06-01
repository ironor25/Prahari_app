<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Prahari;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
   
    public function index(Request $request)
    {
        $user_id = $request->user_id;
        
        if (!$user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Prahari profile not found'
            ], 404);
        }

        $prahari = Prahari::where('user_id', $user_id)->first();
        if (!$prahari) {
            return response()->json([
                'success' => false,
                'message' => 'Prahari profile not found'
            ], 404);
        }

        $transactions = $prahari->transactions()->latest()->get();

        return response()->json([
            'success' => true,
            'data' => [
                'wallet_balance' => $prahari->wallet_balance,
                'transactions' => $transactions,
            ]
        ]);
    }

    public function store(Request $request)
    {
        $user_id = $request->user_id;
        if (!$user_id) {
            return response()->json(['success' => false, 'message' => 'Prahari profile not found'], 404);
        }

        $prahari = Prahari::where('user_id', $user_id)->first();
        if (!$prahari) {
            return response()->json(['success' => false, 'message' => 'Prahari profile not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1|max:' . $prahari->wallet_balance,
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // Create withdrawal transaction
        $transaction = Transaction::create([
            'prahari_id' => $prahari->id,
            'amount' => $request->amount,
            'bank_account' => $prahari->bank_account,
            'status' => 'Open', 
        ]);

        // $prahari->decrement('wallet_balance', $request->amount);

        return response()->json([
            'success' => true,
            'message' => 'Withdrawal request submitted successfully',
            'data' => $transaction
        ]);
    }
}
