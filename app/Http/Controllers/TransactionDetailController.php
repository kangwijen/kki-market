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
        $user = Auth::user();
        $isAdmin = $user->role_id === 1;

        if (!$isAdmin && $transactionDetail->transactionHeader->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return DB::transaction(function() use ($request, $transactionDetail) {
            $transactionDetail->update($request->validated());
            return response()->json($transactionDetail);
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransactionDetail $transactionDetail)
    {
        //
    }
}
