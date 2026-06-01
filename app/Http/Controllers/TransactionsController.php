<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TransactionsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Transaction::with(['prahari']);

            if ($request->has('type')) {
                if ($request->type == 'requests') {
                    $query->where('status', 'Open');
                } elseif ($request->type == 'history') {
                    $query->whereIn('status', ['Approved', 'Rejected']);
                }
            }

            $transactions = $query->get();
            $dt = DataTables::of($transactions)
                ->addIndexColumn()
                ->addColumn('prahari_name', function ($transaction) {
                    return $transaction->prahari ? $transaction->prahari->name : 'N/A';
                });

            if ($request->type == 'requests') {
                $dt->addColumn('action', function ($transaction) {
                    $btnStyle = "border:none; background:none; font-size:18px; font-weight:bold; cursor:pointer; ";
                    return ' <button class="approveBtn" data-id="'.$transaction->id.'" style="'.$btnStyle.'">
                                    <i class="bi bi-check-lg"></i>
                                </button>
                                <button class="rejectBtn " data-id="'.$transaction->id.'" style="'.$btnStyle.'">
                                    <i class="bi bi-x-lg"></i>
                                </button>';
                })
                ->rawColumns(['action']);
            } else {
                $dt->addColumn('action', function ($transaction) {
                    return '';
                });
            }

            return $dt->make(true);
        }
        return view('layouts.admin.payments');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Approved,Rejected',
        ]);

        $transaction = Transaction::findOrFail($id);
        $transaction->update(['status' => $request->status]);

        return response()->json(['success' => 'Transaction ' . $request->status . ' successfully.']);
    }

    public function create()
    {
        return view('admin.transactions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'withdrawal_id' => 'nullable|string|max:255|unique:transactions,withdrawal_id',
            'prahari_id' => 'nullable|exists:praharis,id',
            'amount' => 'nullable|numeric|min:0',
            'bank_account' => 'nullable|string|max:255',
            'status' => 'required|in:Open,Approved,Rejected',
        ]);

        Transaction::create($validated);

        return redirect()->route('admin.transactions.index')->with('success', 'Transaction created successfully.');
    }

    public function show($id)
    {
        $transaction = Transaction::findOrFail($id);
        return view('admin.transactions.show', compact('transaction'));
    }

    public function edit($id)
    {
        $transaction = Transaction::findOrFail($id);
        return view('admin.transactions.edit', compact('transaction'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'withdrawal_id' => 'nullable|string|max:255|unique:transactions,withdrawal_id,' . $id,
            'prahari_id' => 'nullable|exists:praharis,id',
            'amount' => 'nullable|numeric|min:0',
            'bank_account' => 'nullable|string|max:255',
            'status' => 'required|in:Open,Approved,Rejected',
        ]);

        $transaction = Transaction::findOrFail($id);
        $transaction->update($validated);

        return redirect()->route('admin.transactions.index')->with('success', 'Transaction updated successfully.');
    }

    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();

        return redirect()->route('admin.transactions.index')->with('success', 'Transaction deleted successfully.');
    }
}
