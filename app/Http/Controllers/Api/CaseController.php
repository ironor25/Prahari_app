<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cases;
use App\Models\Prahari;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CaseController extends Controller
{
    /**
     * List all cases for the authenticated Prahari.
     */
    public function index(Request $request)
    {
        $user_id = $request->user_id;
        if (!$user_id) {
            return response()->json(['success' => false, 'message' => 'User not authenticated'], 404);
        }

        $prahari = Prahari::where('user_id', $user_id)->first();
        if (!$prahari) {
            return response()->json(['success' => false, 'message' => 'Prahari profile not found'], 404);
        }

        $query = Cases::where('prahari_id', $prahari->id);
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
       
        $cases = $query->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Cases list',
            'data' => $cases
        ]);
    }

    /**
     * Store a new case.
     */
    public function store(Request $request)
    {
        $user_id = $request->user_id;
        if (!$user_id) {
            return response()->json(['success' => false, 'message' => 'User not authenticated'], 404);
        }

        $prahari = Prahari::where('user_id', $user_id)->first();
        if (!$prahari) {
            return response()->json(['success' => false, 'message' => 'Prahari profile not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'case_category_id' => 'required|exists:case_categories,id',
            'vehicle_number' => 'required|string|max:20',
            'location' => 'required|string',
            'evidence' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov|max:10240', // 10MB limit
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $lastCase = Cases::where('case_id', 'like', 'CASE%')->latest('id')->first();
        if ($lastCase && preg_match('/CASE(\d+)/', $lastCase->case_id, $matches)) {
            $nextNumber = (int)$matches[1] + 1;
            $nextCaseId = 'CASE' . $nextNumber;
        } else {
            $nextCaseId = 'CASE2309';
        }

        $evidencePath = null;
        try{
        if ($request->hasFile('evidence')) {
            $file = $request->file('evidence');
            $extension = $file->getClientOriginalExtension() ?: 'mp4';
            $fileName = $nextCaseId . '.' . $extension;
            $destinationPath = public_path('assets/video');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            // Move file to public/assets/video/
            $file->move($destinationPath, $fileName);
            
            $evidencePath = 'assets/video/' . $fileName;
        }
            }
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
        
        $case = Cases::create([
            'case_id' => $nextCaseId,
            'prahari_id' => $prahari->id,
            'case_category_id' => $request->case_category_id,
            'vehicle_number' => $request->vehicle_number,
            'location' => $request->location,
            'evidence' => $evidencePath,
            'status' => 'Open',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Case created successfully',
            'data' => $case
        ]);
    }

    /**
     * Show case details.
     */
    public function show(int $id)
    {
        $case = Cases::where('id', $id)->with('caseCategory', 'challan')->first();
        if (!$case) {
            return response()->json(['success' => false, 'message' => 'Case not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $case
        ]);
    }

    
}
