<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Challan;
use App\Models\Prahari;
use Illuminate\Http\Request;

class ChallanController extends Controller
{
    
    public function index(Request $request)
    {
        $user_id = $request->user_id;
       
        if (!$user_id) {
            return response()->json(['success' => false, 'message' => 'Prahari profile not found'], 404);
        }

        $prahari = Prahari::where('user_id', $user_id)->first();
        if (!$prahari) {
            return response()->json(['success' => false, 'message' => 'Prahari profile not found'], 404);
        }
        
        $query = Challan::where('prahari_id', $prahari->id)->with('cases');
        
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $challans = $query->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Challans list',
            'data' => $challans
        ]);
    }

    public function show($id,Request $request)
    {
        $user_id = $request->user_id;
        
        if (!$user_id) {
            return response()->json(['success' => false, 'message' => 'Prahari profile not found'], 404);
        }

        $prahari = Prahari::where('user_id', $user_id)->first();
        if (!$prahari) {
            return response()->json(['success' => false, 'message' => 'Prahari profile not found'], 404);
        }

        $challan = Challan::where('prahari_id', $prahari->id)->with('cases', 'category')->find($id);

        if (!$challan) {
            return response()->json(['success' => false, 'message' => 'Challan not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $challan
        ]);
    }
}
