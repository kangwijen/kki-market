<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreCartRequest;
use App\Http\Requests\UpdateCartRequest;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cartItems = Cart::with('product.productDetail')
            ->where('user_id', Auth::id())
            ->get();
            
        return response()->json($cartItems);
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
    public function store(StoreCartRequest $request)
    {   
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
            ],
            [
                'quantity' => $request->quantity,
            ]
        );

        return response()->json($cart, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCartRequest $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart::where('user_id', Auth::id())
            ->where('product_id', $id)
            ->firstOrFail();

        $cart->update(['quantity' => $request->quantity]);

        return response()->json($cart);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Cart::where('user_id', Auth::id())
            ->where('product_id', $id)
            ->delete();

        return response()->json(null, 204);
    }
}
