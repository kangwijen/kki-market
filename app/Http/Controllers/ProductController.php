<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('productDetail', 'productType')->get();
        $products = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'img_path' => $product->img_path,
                'product_type' => $product->productType->name,
                'product_detail' => [
                    'price' => $product->productDetail->price,
                    'stock' => $product->productDetail->stock,
                    'sold' => $product->productDetail->sold,
                ],
            ];
        });

        return response()->json($products);
    }

    public function indexAdmin()
    {
        $products = Product::with('productDetail', 'productType')->get();
        return response()->json($products);
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
    public function store(StoreProductRequest $request)
    {
        try {
            DB::beginTransaction();

            if (Auth::user()->role_id !== 1) {
                return response()->json(['error' => 'You are not authorized to create products.'], 403);
            }

            $product = Product::create($request->except('product_detail'));

            $productDetailData = $request->input('product_detail');
            $productDetail = new ProductDetail($productDetailData);
            $product->productDetail()->save($productDetail);

            DB::commit();

            $product->load('productDetail', 'productType');

            return response()->json($product, 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to create product: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        
        $product = Product::with('productDetail', 'productType')->findOrFail($id);
        // Get only the necessary data
        $product = [
            'id' => $product->id,
            'name' => $product->name,
            'img_path' => $product->img_path,
            'product_type' => $product->productType->name,
            'product_detail' => [
                'price' => $product->productDetail->price,
                'stock' => $product->productDetail->stock,
                'sold' => $product->productDetail->sold,
            ],
        ];
        
        return response()->json($product);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        try {
            DB::beginTransaction();

            $product->update($request->except('product_detail'));

            if ($request->has('product_detail')) {
                $product->productDetail()->update($request->input('product_detail'));
            }

            DB::commit();

            $product->load('productDetail', 'productType');

            return response()->json($product);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to update product: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            DB::beginTransaction();

            if (Auth::user()->role_id !== 1) {
                return response()->json(['error' => 'You are not authorized to delete this product.'], 403);
            }

            $product->productDetail()->delete();
            $product->delete();

            DB::commit();
            return response()->json(['message' => 'Product deleted successfully.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to delete product: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Upload an image for a product.
     */
    public function uploadImage(Request $request)
    {
        try {
            if (Auth::user()->role_id !== 1) {
                return response()->json(['error' => 'You are not authorized to upload images.'], 403);
            }

            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);
    
            $extension = $request->file('image')->getClientOriginalExtension();
            $filename = uniqid('product_', true) . '.' . $extension;
    
            $imagePath = $request->file('image')->storeAs('products', $filename, 'public');
    
            return response()->json(['image_path' => $imagePath]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to upload image: ' . $e->getMessage()], 500);
        }
    }
    
}
