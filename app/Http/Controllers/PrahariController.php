<?php

namespace App\Http\Controllers;

use App\Models\Prahari;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PrahariController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $praharis = Prahari::all();
            return DataTables::of($praharis)
                    ->addIndexColumn()
                    ->addColumn('action', function ($prahari) use ($praharis) {
                       $btnStyle = "border:none; background:none; font-size:18px; font-weight:bold; cursor:pointer; margin-right:8px;";
                        return ' 
                        <button class="editBtn" data-id="'.$prahari->id.'" style="'.$btnStyle.'">
                                    <i class="bi bi-pencil"></i>
                                </button>
                        <button class="deleteBtn" data-id="'.$prahari->id.'" style="'.$btnStyle.'">
                                    <i class="bi bi-trash"></i>
                                </button>';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('layouts.admin.prahari');
    }

    public function create()
    {
        return view('admin.praharis.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:15',
            'bank_account' => 'required|string|max:255',
            'aadhaar_status' => 'required|in:verified,not_verified',
            'status' => 'required|in:active,inactive',
        ]);

        Prahari::create($validated);

        if ($request->ajax()) {
            return response()->json(['success' => 'Prahari created successfully.']);
        }

        return redirect()->route('admin.praharis.index')->with('success', 'Prahari created successfully.');
    }

    public function show($id)
    {
        $prahari = Prahari::findOrFail($id);
        return view('admin.praharis.show', compact('prahari'));
    }

    public function edit($id)
    {
        $prahari = Prahari::findOrFail($id);
        return response()->json($prahari);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:15',
            'bank_account' => 'required|string|max:255',
            'aadhaar_status' => 'required|in:verified,not_verified',
            'status' => 'required|in:active,inactive',
        ]);

        $prahari = Prahari::findOrFail($id);
        $prahari->update($validated);

        if ($request->ajax()) {
            return response()->json(['success' => 'Prahari updated successfully.']);
        }

        return redirect()->route('admin.praharis.index')->with('success', 'Prahari updated successfully.');
    }

    public function destroy($id)
    {
        $prahari = Prahari::findOrFail($id);
        $prahari->delete();

        if (request()->ajax()) {
            return response()->json(['success' => 'Prahari deleted successfully.']);
        }

        return redirect()->route('admin.praharis.index')->with('success', 'Prahari deleted successfully.');
    }
}
