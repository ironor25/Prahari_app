<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cases;
use App\Models\Challan;
use App\Models\Prahari;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics for the authenticated Prahari.
     */
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

        $total_cases = Cases::join('praharis', 'cases.prahari_id', '=', 'praharis.id')
            ->where('praharis.user_id', $user_id)
            ->count();

        $total_challans = Challan::join('praharis', 'challans.prahari_id', '=', 'praharis.id')
            ->where('praharis.user_id', $user_id)
            ->count();
        
        // Assuming earnings come from paid challans
        $total_earnings = Challan::join('praharis', 'challans.prahari_id', '=', 'praharis.id')
            ->where('praharis.user_id', $user_id)
            ->where('challans.status', 'paid')
            ->sum('challans.fine_amount');

        $recent_cases = Cases::join('praharis', 'cases.prahari_id', '=', 'praharis.id')
            ->where('praharis.user_id', $user_id)
            ->select('cases.*')
            ->latest('cases.created_at')
            ->take(5)
            ->get();

        $recent_challans = Challan::join('praharis', 'challans.prahari_id', '=', 'praharis.id')
            ->where('praharis.user_id', $user_id)
            ->select('challans.*')
            ->latest('challans.created_at')
            ->take(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'prahari_id'=> $prahari->prahari_id,
                'total_cases' => $total_cases,
                'total_challans' => $total_challans,
                'total_earnings' => $total_earnings,
                'recent_cases' => $recent_cases,
                'recent_challans' => $recent_challans,
            ]
        ]);
    }
}
