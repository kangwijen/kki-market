<?php

namespace App\Http\Controllers;

use App\Models\ProductDetail;
use App\Http\Requests\StoreProductDetailRequest;
use App\Http\Requests\UpdateProductDetailRequest;
use Illuminate\Http\Request;

class ProductDetailController extends Controller
{
    /**
     * Pixel Drain service instance.
     */
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://pixeldrain.com/api/',
        ]);
    }

    public function uploadFile(string $file): string
    {
        try{
            $response = $this->client->post('upload', [
                'multipart' => [
                    [
                        'name' => 'file',
                        'contents' => fopen($file, 'r'),
                        'filename' => $file->getClientOriginalName(),
                    ],
                ],
            ]);
    
            return json_decode($response->getBody()->getContents(), true);
    
        } catch (\Exception $e) {
            Log::error('Failed to upload file to PixelDrain', [
                'message' => $e->getMessage(),
            ]);
    
            return '';
        }
    }

    public function downloadFile(string $fileId): string
    {
        try{
            $response = $this->client->get("file/{$fileId}");

            return json_decode($response->getBody()->getContents(), true);

        } catch (\Exception $e) {
            Log::error('Failed to download file from PixelDrain', [
                'message' => $e->getMessage(),
            ]);

            return '';
        }
    }

    public function deleteFile(string $fileId): bool
    {
        try{
            $response = $this->client->delete("file/{$fileId}");

            return $response->getStatusCode() === 200;

        } catch (\Exception $e) {
            Log::error('Failed to delete file from PixelDrain', [
                'message' => $e->getMessage(),
            ]);

            return false;
        }
    }

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
    public function store(StoreProductDetailRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductDetail $productDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductDetail $productDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductDetailRequest $request, ProductDetail $productDetail)
    {
        $productDetail->update($request->validated());
        return response()->json($productDetail);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductDetail $productDetail)
    {
        //
    }
}
