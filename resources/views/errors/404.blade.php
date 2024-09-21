@extends('layouts.base')

@section('content')
    <div class="flex items-center justify-center min-h-screen bg-base-300">
        <div class="text-center">
            <h1 class="font-black text-red-500 text-9xl">404</h1>

            <p class="text-2xl font-bold tracking-tight text-white sm:text-4xl">Uh-oh!</p>

            <p class="mt-4 text-white">We can't find that page.</p>

            <a href="{{ route('index') }}" class="mt-6 btn btn-primary">
                Go Back Home
            </a>
        </div>
    </div>
@endsection