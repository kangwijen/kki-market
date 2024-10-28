<?php

namespace App\Http\Controllers;

use App\Models\ProductType;
use App\Http\Requests\StoreProductTypeRequest;
use App\Http\Requests\UpdateProductTypeRequest;

class ProductTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productTypes = ProductType::all();
        return response()->json($productTypes);
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
    public function store(StoreProductTypeRequest $request)
    {
        $productType = ProductType::create($request->validated());
        return response()->json($productType);
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductType $productType)
    {
        return response()->json($productType);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductType $productType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductTypeRequest $request, ProductType $productType)
    {
        $productType->update($request->validated());

        return response()->json($productType);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductType $productType)
    {
        if ($productType->products()->exists()) {
            return response()->json(['error' => 'There are still products with this product type.'], 400);
        }
    
        $productType->delete();
        return response()->json(['message' => 'Product type deleted successfully']);
    }
    
}
