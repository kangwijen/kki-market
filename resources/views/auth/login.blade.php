@extends('layouts.base')

@section('content')
    <div class="min-h-screen px-4 py-16 mx-auto bg-gray-700 sm:px-6 lg:px-8">
        <div class="flex-grow max-w-lg mx-auto">
            <h1 class="text-2xl font-bold text-center text-white sm:text-3xl">Get started today</h1>
        
            <form action="/login" class="p-4 mt-6 space-y-4 bg-gray-500 rounded-lg shadow-lg sm:p-6 lg:p-8">
                <p class="text-lg font-medium text-center text-white">Sign in to your account</p>
        
                <div>
                <label for="email" class="sr-only">Email</label>
        
                <div class="relative">
                    <input type="email" class="w-full p-4 text-sm border-gray-200 rounded-lg shadow-sm pe-12" placeholder="Enter email">
                </div>
                </div>
        
                <div>
                <label for="password" class="sr-only">Password</label>
        
                <div class="relative">
                    <input type="password" class="w-full p-4 text-sm border-gray-200 rounded-lg shadow-sm pe-12" placeholder="Enter password">
                </div>
                </div>
        
                <button type="submit" class="block w-full px-5 py-3 text-sm font-medium text-white bg-indigo-600 rounded-lg">
                Sign in
                </button>
        
                <p class="text-sm text-center text-gray-200">
                No account?
                <a class="underline" href="/register">Sign up</a>
                </p>
            </form>
        </div>
    </div>
@endsection