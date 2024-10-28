<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
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
        DB::beginTransaction();

        try {
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
        $product->update($request->validated());
        
        if ($request->has('product_detail')) {
            $product->productDetail()->update($request->input('product_detail'));
        }

        return response()->json($product->load('productDetail', 'productType'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully.']);
    }
}
