<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
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

        $existingCartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($existingCartItem) {
            return response()->json(['message' => 'Product already added to cart'], 400);
        }

        $product = Product::with('productDetail')->findOrFail($request->product_id);
        
        if (!$product->productDetail || $product->productDetail->stock === null) {
            return response()->json(['message' => 'Product stock information is unavailable'], 400);
        }

        if ($request->quantity > $product->productDetail->stock) {
            return response()->json(['message' => 'Requested quantity exceeds available stock'], 400);
        }

        $cart = Cart::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
        ]);

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
    public function update(UpdateCartRequest $request, $product_id)
    {
        $cart = Cart::where('user_id', Auth::id())
            ->where('product_id', $product_id)
            ->firstOrFail();

        $product = Product::with('productDetail')->findOrFail($product_id);
        
        if (!$product->productDetail || $product->productDetail->stock === null) {
            return response()->json(['message' => 'Product stock information is unavailable'], 400);
        }

        if ($request->quantity > $product->productDetail->stock) {
            return response()->json(['message' => 'Requested quantity exceeds available stock'], 400);
        }

        $cart = Cart::where('user_id', Auth::id())
            ->where('product_id', $product_id)
            ->update([
                'quantity' => $request->quantity,
        ]);

        return response()->json($cart, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($product_id)
    {
        Cart::where('user_id', Auth::id())
            ->where('product_id', $product_id)
            ->delete();

        return response()->json(null, 204);
    }
}
