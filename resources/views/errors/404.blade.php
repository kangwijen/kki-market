@extends('layouts.base')

@section('content')
    <div class="grid h-screen px-4 bg-gray-700 place-content-center">
        <div class="text-center">
            <h1 class="font-black text-red-500 text-9xl">404</h1>
        
            <p class="text-2xl font-bold tracking-tight text-white sm:text-4xl">Uh-oh!</p>
        
            <p class="mt-4 text-white">We can't find that page.</p>
        
            <a href="/" class="inline-block px-5 py-3 mt-6 text-sm font-medium text-white bg-indigo-600 rounded hover:bg-indigo-700 focus:outline-none focus:ring">
                Go Back Home
            </a>
        </div>
    </div>
@endsection