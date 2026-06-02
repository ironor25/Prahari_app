<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prahari;
use App\Models\Cases;
use App\Models\Challan;
use App\Models\Transaction;
use App\Models\User;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminController extends Controller
{

    private function getCommonStats() {
        $totalPrahari = Prahari::count();
        $totalCases = Cases::count();
        $totalChallans = Challan::count();
        $totalWithdrawals = Transaction::where('status','Approved')->sum('amount');
        $totalPendingWithdrawals = Transaction::where('status','Open')->sum('amount');
        $todaysCases = Cases::whereDate('created_at', Carbon::today())->count();
        $todaysChallans = Challan::whereDate('created_at', Carbon::today())->count();
        $totalActivePrahari = Prahari::where('status','active')->count();
        $totalRevenue = Challan::sum('fine_amount');

        // Line Chart Data: Cases by Category
        $casesByCategory = Cases::join('case_categories', 'cases.case_category_id', '=', 'case_categories.id')
            ->selectRaw('case_categories.case_category_name as category, COUNT(cases.id) as count')
            ->groupBy('case_categories.case_category_name')
            ->get();
        $caseLabels = $casesByCategory->pluck('category')->toArray();
        $caseData = $casesByCategory->pluck('count')->toArray();

        // Pie Chart Data: Challan by Status
        $challanStatuses = Challan::selectRaw('status, COUNT(id) as count')
            ->groupBy('status')
            ->get();
        // Capitalize labels to look nice (e.g. "pending" -> "Pending")
        $challanLabels = $challanStatuses->pluck('status')->map(function($status) { return ucfirst($status); })->toArray();
        $challanData = $challanStatuses->pluck('count')->toArray();

        // Monthly Challan Trend
        $challanTrend = Challan::selectRaw('TRIM(TO_CHAR(created_at, \'Month\')) as month, COUNT(id) as count, EXTRACT(MONTH FROM created_at) as month_num')
            ->groupByRaw('TRIM(TO_CHAR(created_at, \'Month\')), EXTRACT(MONTH FROM created_at)')
            ->orderBy('month_num')
            ->get();
        $challanTrendLabels = $challanTrend->pluck('month')->toArray();
        $challanTrendData = $challanTrend->pluck('count')->toArray();

        // Monthly Revenue Trend (75% of paid challans)
        $revenueTrend = Challan::where('status', 'Paid')
            ->selectRaw('TRIM(TO_CHAR(created_at, \'Month\')) as month, SUM(fine_amount) * 0.75 as revenue, EXTRACT(MONTH FROM created_at) as month_num')
            ->groupByRaw('TRIM(TO_CHAR(created_at, \'Month\')), EXTRACT(MONTH FROM created_at)')
            ->orderBy('month_num')
            ->get();
        $revenueTrendLabels = $revenueTrend->pluck('month')->toArray();
        $revenueTrendData = $revenueTrend->pluck('revenue')->toArray();

        return compact(
            'totalPrahari', 
            'totalCases', 
            'totalChallans', 
            'totalWithdrawals',
            'totalPendingWithdrawals',
            'todaysCases',
            'todaysChallans',
            'totalActivePrahari',
            'totalRevenue',
            'caseLabels',
            'caseData',
            'challanLabels',
            'challanData',
            'challanTrendLabels',
            'challanTrendData',
            'revenueTrendLabels',
            'revenueTrendData'
        );
    }

    public function adminDashboard(){
        return view('layouts.admin.dashboard', $this->getCommonStats());
    }

    public function reports() {
        return view('layouts.admin.reports', $this->getCommonStats());
    }

    public function settings() {
        return view('layouts.admin.settings', $this->getCommonStats());
    }

    public function admins(Request $request) {
        if ($request->ajax()) {
            $users = User::all();
            return DataTables::of($users)
                ->addIndexColumn()
                ->make(true);
        }
        return view('layouts.admin.admins');
    }

    public function storeAdmin(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json(['success' => 'Admin created successfully.']);
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
