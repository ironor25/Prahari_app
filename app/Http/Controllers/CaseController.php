<?php

namespace App\Http\Controllers;

use App\Models\CaseCategory;
use App\Models\Cases;
use App\Models\Challan;
use App\Models\Prahari;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CaseController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Cases::with(['prahari', 'caseCategory']);
            $cases = $query->get();
            
            $dt = DataTables::of($cases)
                    ->addIndexColumn()
                    ->addColumn('prahari_name', function ($case) {
                        return $case->prahari ? $case->prahari->name : 'N/A';
                    })
                    ->addColumn('category_name', function ($case) {
                        return $case->caseCategory ? $case->caseCategory->case_category_name : 'N/A';
                    })
                    ->addColumn('created_at', function ($case) {
                        return $case->created_at ? $case->created_at->format('d M Y') : 'N/A';
                    })
                    ->addColumn('action', function ($case) {
                        $btnStyle = "border:none; background:none; font-size:18px; font-weight:bold; cursor:pointer; ";
                        if ($case->status == 'Open') {
                            return '
                                <button class="viewBtn" data-id="'.$case->id.'" style="'.$btnStyle.'">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="approveBtn" data-id="'.$case->id.'" style="'.$btnStyle.'">
                                    <i class="bi bi-check-lg"></i>
                                </button>
                                <button class="rejectBtn " data-id="'.$case->id.'" style="'.$btnStyle.'">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            ';
                        } else {
                            return '
                                <button class="viewBtn " data-id="'.$case->id.'" style="'.$btnStyle.'">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="deleteBtn " data-id="'.$case->id.'" style="'.$btnStyle.'">
                                    <i class="bi bi-trash"></i>
                                </button>
                            ';
                        }
                    })->rawColumns(['action']);

            return $dt->make(true);
        }
        $praharis = Prahari::all();
        $categories = CaseCategory::all();
        return view('layouts.admin.cases', compact('praharis', 'categories'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Approved,Rejected',
        ]);
        
        $case = Cases::with('caseCategory')->findOrFail($id);
        $case->update(['status' => $request->status]);

 

        return response()->json(['success' => 'Case ' . $request->status . ' successfully.']);
    }

    public function create()
    {
        return view('admin.cases.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'case_id' => 'nullable|string|max:255|unique:cases,case_id',
            'prahari_id' => 'nullable|exists:praharis,id',
            'case_category_id' => 'nullable|exists:case_categories,id',
            'vehicle_number' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:Open,Approved,Rejected',
        ]);

        Cases::create($validated);

        if ($request->ajax()) {
            return response()->json(['success' => 'Case created successfully.']);
        }

        return redirect()->route('admin.cases.index')->with('success', 'Case created successfully.');
    }

    public function show(int $id)
    {
        $case = Cases::with('caseCategory')->findOrFail($id);
        if (request()->ajax()) {
            $data = $case->toArray();
            $data['category_name'] = $case->caseCategory ? $case->caseCategory->case_category_name : 'N/A';
            return response()->json($data);
        }
        return view('admin.cases.show', compact('case'));
    }

    public function edit(int $id)
    {
        $case = Cases::findOrFail($id);
        if (request()->ajax()) {
            return response()->json($case);
        }
        return view('admin.cases.edit', compact('case'));
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'case_id' => 'nullable|string|max:255|unique:cases,case_id,' . $id,
            'prahari_id' => 'nullable|exists:praharis,id',
            'case_category_id' => 'nullable|exists:case_categories,id',
            'vehicle_number' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:Open,Approved,Rejected',
        ]);

        $case = Cases::findOrFail($id);
        $case->update($validated);

        if ($request->ajax()) {
            return response()->json(['success' => 'Case updated successfully.']);
        }

        return redirect()->route('admin.cases.index')->with('success', 'Case updated successfully.');
    }

    public function destroy(int $id)
    {
        $case = Cases::findOrFail($id);
        $case->delete();

        if (request()->ajax()) {
            return response()->json(['success' => 'Case deleted successfully.']);
        }

        return redirect()->route('admin.cases.index')->with('success', 'Case deleted successfully.');
    }
}
