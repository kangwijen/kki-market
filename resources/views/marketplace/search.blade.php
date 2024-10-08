@extends('layouts.base')

@section('content')
<div class="min-h-screen p-8 bg-base-300">
    <div class="mb-6">
        <label for="search" class="block mb-2 text-lg font-bold">Search for products:</label>
        <input type="text" id="search" placeholder="Type here..." class="min-w-full min-w-xl input input-bordered input-primary" />
    </div>
    
    <div class="grid grid-cols-4 gap-6">
        <div class="col-span-1 p-4 rounded-lg shadow bg-base-200">
            <h3 class="mb-4 text-lg font-bold">Filter by</h3>
            <div class="mb-4">
                <label class="block mb-2 font-bold">Category</label>
                <select class="w-full select select-bordered">
                    <option disabled selected>Choose a category</option>
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block mb-2 font-bold">Brand</label>
                <select class="w-full select select-bordered">
                    <option disabled selected>Choose a brand</option>
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block mb-2 font-bold">Price Range</label>
                <input type="range" min="0" max="200" value="100" class="range range-primary" />
                <div class="flex justify-between mt-2 text-sm">
                    <span>$0</span>
                    <span>$200+</span>
                </div>
            </div>
        </div>
    
        <div class="col-span-3">
            <div class="flex justify-between mb-6">
                <h2 class="text-xl font-bold">Products</h2>
                <input type="text" id="search" placeholder="Search products..." class="input input-bordered" />
            </div>
            <div class="grid grid-cols-3 gap-4">

                @foreach($products as $product)
                    <div class="shadow-xl card bg-base-100">
                        <figure class="relative">
                            <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="object-cover w-full h-48" />
                            @if(isset($product['discount']))
                                <span class="absolute px-2 py-1 text-xs text-white rounded-full top-2 left-2 bg-primary">{{ $product['discount'] }}% Off</span>
                            @endif
                        </figure>
                        <div class="card-body">
                            <h3 class="card-title">{{ $product['name'] }}</h3>
                            <p class="text-lg text-primary">${{ $product['price'] }}</p>
                            <div class="justify-between card-actions">
                                <a href="{{ url('products', ['id' => $product['id']]) }}" class="btn btn-secondary">Details</a>
                                <button class="btn btn-primary">Add to Cart</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            
                
            </div>
        </div>
    </div>

    <div class="flex justify-center mt-8">
        <div class="join">
            <button class="join-item btn">1</button>
            <button class="join-item btn btn-active">2</button>
            <button class="join-item btn">3</button>
            <button class="join-item btn">4</button>
        </div>
    </div>

</div>
@endsection