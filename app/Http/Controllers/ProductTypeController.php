<?php

namespace App\Http\Controllers;

use App\Models\ProductType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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
        try {
            DB::beginTransaction();

            if (Auth::user()->role_id !== 1) {
                return response()->json(['error' => 'You are not authorized to create product types.'], 403);
            }
            
            $productType = ProductType::create($request->validated());

            DB::commit();

            return response()->json($productType, 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to create product type: ' . $e->getMessage()], 500);
        }
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
        try {
            DB::beginTransaction();

            if (Auth::user()->role_id !== 1) {
                return response()->json(['error' => 'You are not authorized to update product types.'], 403);
            }

            $productType->update($request->validated());

            DB::commit();

            return response()->json($productType);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to update product type: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductType $productType)
    {
        try {
            DB::beginTransaction();

            if (Auth::user()->role_id !== 1) {
                return response()->json(['error' => 'You are not authorized to delete product types.'], 403);
            }

            if ($productType->products()->exists()) {
                return response()->json(['error' => 'Cannot delete product type with associated products'], 400);
            }
            
            $productType->delete();

            DB::commit();

            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to delete product type: ' . $e->getMessage()], 500);
        }
    }
}
