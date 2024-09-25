@extends('layouts.base')

@section('content')
<div class="min-h-screen p-8 bg-base-300">
    <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
        <div class="flex flex-col space-y-6 md:col-span-2">
            <div class="flex justify-center max-w-full p-4 rounded-md shadow lg:max-w-screen">
                <img class="w-auto h-auto md:h-[500px] object-cover rounded-md bg-center" src="{{ $p['image'] }}" alt="{{ $p['name'] }}">
            </div>
            
    
            <div class="p-6 rounded-md shadow">
                <h1 class="mb-4 text-2xl font-bold">{{ $p['name'] }}</h1>
                
                <div class="flex items-center gap-2 mb-4">
                    <span class="badge badge-primary badge-lg">{{ $p['stock'] }} in stock</span>
                    <span class="badge badge-secondary badge-lg">{{ $p['sold'] }}+ Sold</span>
                </div>
                
                <div class="mb-4">
                    <span class="text-xl">{{ "$" . number_format($p['price'], 0, ',', '.') }}</span>
                </div>

                <div class="mb-4">
                    <p>{{ $p['description'] }}</p>
                </div>
            </div>
        </div>

        <div class="w-full p-6 shadow-xl card bg-base-100">
            <h2 class="text-2xl font-semibold">Buy Now</h2>
            <div class="mt-6 space-y-4">
                <button class="w-full btn btn-primary">Add to Cart</button>
                <button class="w-full btn btn-secondary">Buy Now</button>
            </div>
        </div>
    </div>
</div>

@endsection
