<?php

namespace App\Http\Controllers;

use App\Models\ProductType;
use App\Http\Requests\StoreProductTypeRequest;
use App\Http\Requests\UpdateProductTypeRequest;
use Illuminate\Support\Facades\DB;

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
        return DB::transaction(function() use ($request) {
            $productType = ProductType::create($request->validated());
            return response()->json($productType, 201);
        });
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
        return DB::transaction(function() use ($request, $productType) {
            $productType->update($request->validated());
            return response()->json($productType);
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductType $productType)
    {
        return DB::transaction(function() use ($productType) {
            if ($productType->products()->exists()) {
                return response()->json(['error' => 'Cannot delete product type with associated products'], 400);
            }
            
            $productType->delete();
            return response()->json(null, 204);
        });
    }
}
