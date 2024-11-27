<?php

namespace App\Http\Controllers;

use App\Models\TransactionDetail;
use App\Http\Requests\StoreTransactionDetailRequest;
use App\Http\Requests\UpdateTransactionDetailRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
    public function store(StoreTransactionDetailRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TransactionDetail $transactionDetail)
    {
        $user = Auth::user();
        $isAdmin = $user->role_id === 1;

        if (!$isAdmin && $transactionDetail->transactionHeader->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($transactionDetail->load('product.productDetail'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TransactionDetail $transactionDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionDetailRequest $request, TransactionDetail $transactionDetail)
    {
        try {
            DB::beginTransaction();

            if (Auth::user()->role_id !== 1) {
                return response()->json(['error' => 'You are not authorized to update transaction details.'], 403);
            }

            $transactionDetail->update($request->validated());

            DB::commit();

            return response()->json($transactionDetail);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to update transaction detail: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransactionDetail $transactionDetail)
    {
        //
    }
}
