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
        $transactionHeaders = TransactionHeader::where('user_id', Auth::id())
            ->with([
                'transactionDetails.product' => function($query) {
                    $query->select('id', 'name', 'img_path');  // Add img_path to selection
                }
            ])
            ->get();

        $transactionHeaders = $transactionHeaders->map(function($transactionHeader) {
            return [
                'id' => $transactionHeader->id,
                'created_at' => $transactionHeader->created_at,
                'transaction_details' => $transactionHeader->transactionDetails->map(function($transactionDetail) {
                    return [
                        'product_id' => $transactionDetail->product_id,
                        'quantity' => $transactionDetail->quantity,
                        'price' => $transactionDetail->price,
                        'total_price' => $transactionDetail->quantity * $transactionDetail->price,
                        'product' => [
                            'id' => $transactionDetail->product->id,
                            'name' => $transactionDetail->product->name,
                            'img_path' => $transactionDetail->product->img_path,
                            'url' => $transactionDetail->product->productDetail->url
                        ]
                    ];
                })
            ];
        });

        return response()->json($transactionHeaders);
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
        //
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
