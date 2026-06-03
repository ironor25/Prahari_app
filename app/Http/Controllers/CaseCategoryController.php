<?php

namespace App\Http\Controllers;

use App\Models\CaseCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CaseCategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $categories = CaseCategory::all();
            return DataTables::of($categories)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                         $btnStyle = "border:none; background:none; font-size:18px; font-weight:bold; cursor:pointer; margin-right:8px;";
                            return ' <button class="editBtn" data-id="'.$row->id.'" style="'.$btnStyle.'">
                                        <i class="bi bi-pencil"></i>
                                    </button>';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('layouts.admin.categories');
    }

    public function create()
    {
        return view('admin.case_categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'case_category_name' => 'required|string|max:255',
            'fine_amount' => 'required|numeric|min:0',
        ]);

        CaseCategory::create($validated);

        if ($request->ajax()) {
            return response()->json(['success' => 'Case category created successfully.']);
        }

        return redirect()->route('admin.case_categories.index')->with('success', 'Case category created successfully.');
    }

    public function show($id)
    {
        $caseCategory = CaseCategory::findOrFail($id);
        if (request()->ajax()) {
            return response()->json($caseCategory);
        }
        return view('admin.case_categories.show', compact('caseCategory'));
    }

    public function edit($id)
    {
        $caseCategory = CaseCategory::findOrFail($id);
        return response()->json($caseCategory);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'case_category_name' => 'required|string|max:255',
            'fine_amount' => 'required|numeric|min:0',
        ]);

        $caseCategory = CaseCategory::findOrFail($id);
        $caseCategory->update($validated);

        if ($request->ajax()) {
            return response()->json(['success' => 'Category updated successfully!']);
        }

        return redirect()->route('admin.case_categories.index')->with('success', 'Case category updated successfully.');
    }

    public function updateAjax(Request $request, $id)
    {
        $validated = $request->validate([
            'case_category_name' => 'required|string|max:255',
            'fine_amount' => 'required|numeric|min:0',
        ]);

        $caseCategory = CaseCategory::findOrFail($id);
        $caseCategory->update($validated);

        return response()->json(['success' => 'Category updated successfully!']);
    }

    public function destroy(Request $request, $id)
    {
        $caseCategory = CaseCategory::findOrFail($id);
        $caseCategory->delete();

        if ($request->ajax()) {
            return response()->json(['success' => 'Category deleted successfully.']);
        }

        return redirect()->route('admin.case_categories.index')->with('success', 'Case category deleted successfully.');
    }
}

