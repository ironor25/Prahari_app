<?php

namespace App\Http\Controllers;

use App\Models\Cases;
use App\Models\Challan;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ChallanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $challans = Challan::with(['prahari', 'category', 'cases'])->get();
            return DataTables::of($challans)
                    ->addIndexColumn()
                    ->addColumn('prahari_custom_id', function ($challan) {
                        return $challan->prahari ? $challan->prahari->prahari_id : 'N/A';
                    })
                    ->addColumn('case_custom_id', function ($challan) {
                        return $challan->cases ? $challan->cases->case_id : 'N/A';
                    })
                    ->addColumn('prahari_name', function ($challan) {
                        return $challan->prahari ? $challan->prahari->name : 'N/A';
                    })
                    ->addColumn('created_at', function ($challan) {
                        return $challan->created_at ? $challan->created_at->format('d M Y') : 'N/A';
                    })
                    ->addColumn('action', function ($challan) {
                        $btnStyle = "border:none; background:none; font-size:18px; font-weight:bold; cursor:pointer; margin-right:8px;";
                        return ' <button class="deleteBtn" data-id="'.$challan->id.'" style="'.$btnStyle.'">
                                    <i class="bi bi-trash"></i>
                                </button>';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        $praharis = \App\Models\Prahari::all();
        $categories = \App\Models\CaseCategory::all();
        return view('layouts.admin.challans', compact('praharis', 'categories'));
    }

    public function create()
    {
        return view('admin.challans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'prahari_id' => 'nullable|exists:praharis,id',
            'case_id' => 'nullable|exists:cases,id',
            'category_id' => 'nullable|exists:case_categories,id',
            'vehicle_number' => 'nullable|string|max:255',
            'fine_amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:paid,cancelled,pending',
        ]);

        Challan::create($validated);

        if ($request->ajax()) {
            return response()->json(['success' => 'Challan created successfully.']);
        }

        return redirect()->route('admin.challans.index')->with('success', 'Challan created successfully.');
    }

    public function show($id)
    {
        $challan = Challan::findOrFail($id);
        return view('admin.challans.show', compact('challan'));
    }

    public function edit($id)
    {
        $challan = Challan::findOrFail($id);
        if (request()->  ajax()) {
            return response()->json($challan);
        }
        return view('admin.challans.edit', compact('challan'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'prahari_id' => 'nullable|exists:praharis,id',
            'category_id' => 'nullable|exists:case_categories,id',
            'vehicle_number' => 'nullable|string|max:255',
            'fine_amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:paid,cancelled,pending',
        ]);

        $challan = Challan::findOrFail($id);
        $challan->update($validated);

        if ($request->ajax()) {
            return response()->json(['success' => 'Challan updated successfully.']);
        }

        return redirect()->route('admin.challans.index')->with('success', 'Challan updated successfully.');
    }

    public function destroy($id)
    {
        $challan = Challan::findOrFail($id);
        $challan->delete();

        if (request()->ajax()) {
            return response()->json(['success' => 'Challan deleted successfully.']);
        }

        return redirect()->route('admin.challans.index')->with('success', 'Challan deleted successfully.');
    }
}
