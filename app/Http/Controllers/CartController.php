<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\UserDetail;
use App\Models\TransactionHeader;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreCartRequest;
use App\Http\Requests\UpdateCartRequest;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!Auth::check() || $request->user()->id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

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
        try {
            DB::beginTransaction();

            if (!Auth::check() || $request->user()->id !== Auth::id()) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            
            $existingCartItem = Cart::where('user_id', Auth::id())
                ->where('product_id', $request->product_id)
                ->exists();

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

            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Product added to cart',
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 400);
        }
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
        try {
            DB::beginTransaction();

            if (!Auth::check() || $request->user()->id !== Auth::id()) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
    
            $cart = Cart::where('user_id', Auth::id())
                ->where('product_id', $product_id)
                ->firstOrFail();
    
            $product = Product::with('productDetail')->findOrFail($product_id);
            
            if (!$product->productDetail || $product->productDetail->stock === null) {
                throw new \Exception('Product stock information is unavailable');
            }
    
            if ($request->quantity > $product->productDetail->stock) {
                throw new \Exception('Requested quantity exceeds available stock');
            }
    
            $cart = Cart::where('user_id', Auth::id())
                ->where('product_id', $product_id)
                ->update([
                    'quantity' => $request->quantity,
                ]);
    
            DB::commit();
            return response()->json($cart, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $product_id)
    {
        if (!Auth::check() || $request->user()->id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        Cart::where('user_id', Auth::id())
            ->where('product_id', $product_id)
            ->delete();

        return response()->json(null, 204);
    }

    /**
     * Checkout the cart.
     */
    public function checkout()
    {
        try {
            return DB::transaction(function() {
                $cartItems = Cart::with('product.productDetail')
                    ->where('user_id', Auth::id())
                    ->lockForUpdate()
                    ->get();

                $userDetail = UserDetail::where('user_id', Auth::id())
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($cartItems->isEmpty()) {
                    return response()->json(['message' => 'Cart is empty'], 400);
                }
                
                $cartTotal = 0;
        
                foreach ($cartItems as $cartItem) {
                    $product = Product::with('productDetail')->findOrFail($cartItem->product_id);
        
                    if (!$product->productDetail || $product->productDetail->stock === null) {
                        return response()->json(['message' => 'Product is unavailable'], 400);
                    }
        
                    if ($cartItem->quantity > $product->productDetail->stock) {
                        return response()->json(['message' => 'Requested quantity exceeds available stock'], 400);
                    }
    
                    if ($cartItem->quantity < 1) {
                        return response()->json(['message' => 'Invalid quantity'], 400);
                    }
        
                    $cartTotal += $product->productDetail->price * $cartItem->quantity;
                }
    
                if ($userDetail->balance < $cartTotal) {
                    return response()->json(['message' => 'Insufficient balance'], 400);
                }
        
                foreach ($cartItems as $cartItem) {
                    $product = Product::with('productDetail')->findOrFail($cartItem->product_id);
                    $product->productDetail->decrement('stock',$cartItem->quantity);
                    $product->productDetail->increment('sold',$cartItem->quantity);
                }
                    
                UserDetail::where('user_id', Auth::id())
                    ->decrement('balance', $cartTotal);
    
                $transaction = TransactionHeader::create([
                    'user_id' => Auth::id()
                ]);
    
                foreach ($cartItems as $cartItem) {
                    $transaction->transactionDetails()->create([
                        'product_id' => $cartItem->product_id,
                        'quantity' => $cartItem->quantity,
                        'price' => $cartItem->product->productDetail->price,
                        'total_price' => $cartItem->quantity *$cartItem->product->productDetail->price,
                    ]);
                }
                    
                Cart::where('user_id', Auth::id())->delete();
    
                $transaction->load('transactionDetails.product.productDetail');
    
                return response()->json([
                    'message' => 'Checkout successful'
                ], 200);
            }, 5);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Transaction failed'], 500);
        }
    }
}
