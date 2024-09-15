@extends('layouts.base')

@section('content')
    <section class="bg-white">
        <div class="lg:grid lg:min-h-screen lg:grid-cols-12">
        <section class="relative flex items-end h-32 bg-gray-900 lg:col-span-5 lg:h-full xl:col-span-6">
            <img alt="" src="https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?q=80"
            class="absolute inset-0 object-cover w-full h-full opacity-80"/>
            
            <div class="hidden lg:relative lg:block lg:p-12">
            <a class="block text-white" href="/">
                <span class="sr-only">Home</span>
            </a>
    
            <h2 class="mt-6 text-2xl font-bold text-white sm:text-3xl md:text-4xl">
                Welcome to KKI-Market
            </h2>
            </div>
        </section>
    
        <main class="flex items-center justify-center px-8 py-8 sm:px-12 lg:col-span-7 lg:px-16 lg:py-12 xl:col-span-6">
            <div class="max-w-xl lg:max-w-3xl">
            <div class="relative block mt-2 lg:hidden">
                <h1 class="mt-2 text-2xl font-bold text-gray-900 sm:text-3xl md:text-4xl">
                    Welcome to KKI-Market
                </h1>
            </div>
    
            <form action="/register" class="grid grid-cols-6 gap-6 mt-8">
                <div class="col-span-6 sm:col-span-3">
                <label for="FirstName" class="block text-sm font-medium text-gray-700">
                    First Name
                </label>
    
                <input
                    type="text"
                    id="FirstName"
                    name="first_name"
                    class="w-full mt-1 text-sm text-gray-700 bg-white border-gray-200 rounded-md shadow-sm"
                />
                </div>
    
                <div class="col-span-6 sm:col-span-3">
                <label for="LastName" class="block text-sm font-medium text-gray-700">
                    Last Name
                </label>
    
                <input
                    type="text"
                    id="LastName"
                    name="last_name"
                    class="w-full mt-1 text-sm text-gray-700 bg-white border-gray-200 rounded-md shadow-sm"
                />
                </div>
    
                <div class="col-span-6">
                <label for="Email" class="block text-sm font-medium text-gray-700"> Email </label>
    
                <input
                    type="email"
                    id="Email"
                    name="email"
                    class="w-full mt-1 text-sm text-gray-700 bg-white border-gray-200 rounded-md shadow-sm"
                />
                </div>
    
                <div class="col-span-6 sm:col-span-3">
                <label for="Password" class="block text-sm font-medium text-gray-700"> Password </label>
    
                <input
                    type="password"
                    id="Password"
                    name="password"
                    class="w-full mt-1 text-sm text-gray-700 bg-white border-gray-200 rounded-md shadow-sm"
                />
                </div>
    
                <div class="col-span-6 sm:col-span-3">
                <label for="PasswordConfirmation" class="block text-sm font-medium text-gray-700">
                    Password Confirmation
                </label>
    
                <input
                    type="password"
                    id="PasswordConfirmation"
                    name="password_confirmation"
                    class="w-full mt-1 text-sm text-gray-700 bg-white border-gray-200 rounded-md shadow-sm"
                />
                </div>
    
                <div class="col-span-6">
                <label for="MarketingAccept" class="flex gap-4">
                    <input
                    type="checkbox"
                    id="MarketingAccept"
                    name="marketing_accept"
                    class="bg-white border-gray-200 rounded-md shadow-sm size-5"
                    />
    
                    <span class="text-sm text-gray-700">
                    I want to receive emails about events, product updates and company announcements.
                    </span>
                </label>
                </div>
    
                <div class="col-span-6">
                <p class="text-sm text-gray-500">
                    By creating an account, you agree to our
                    <a href="/terms" class="text-gray-700 underline"> terms and conditions </a>
                    and
                    <a href="/policy" class="text-gray-700 underline">privacy policy</a>.
                </p>
                </div>
    
                <div class="col-span-6 sm:flex sm:items-center sm:gap-4">
                <button
                    class="inline-block px-12 py-3 text-sm font-medium text-white transition bg-blue-600 border border-blue-600 rounded-md shrink-0 hover:bg-transparent hover:text-blue-600 focus:outline-none focus:ring active:text-blue-500"
                >
                    Create an account
                </button>
    
                <p class="mt-4 text-sm text-gray-500 sm:mt-0">
                    Already have an account?
                    <a href="/login" class="text-gray-700 underline">Log in</a>.
                </p>
                </div>
            </form>
            </div>
        </main>
        </div>
    </section>
@endsection