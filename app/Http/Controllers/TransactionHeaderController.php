<?php

namespace App\Http\Controllers;

use App\Models\TransactionHeader;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTransactionHeaderRequest;
use App\Http\Requests\UpdateTransactionHeaderRequest;

class TransactionHeaderController extends Controller
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
    public function store(StoreTransactionHeaderRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TransactionHeader $transactionHeader)
    {
        $user = Auth::user();
        $transactionHeaders = $transactionHeader->where('user_id', $user->id)->with('transactionDetails')->get();
        $transactionHeaders->load('transactionDetails.product');
        return response()->json($transactionHeaders);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TransactionHeader $transactionHeader)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionHeaderRequest $request, TransactionHeader $transactionHeader)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransactionHeader $transactionHeader)
    {
        //
    }
}
