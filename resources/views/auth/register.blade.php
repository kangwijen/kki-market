@extends('layouts.base')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-base-300">
    <div class="w-full max-w-md p-8 space-y-6 rounded-lg shadow-lg bg-base-100">
        <h1 class="text-4xl font-bold text-center text-primary">Register</h1>

        <div class="form-control">
            <label class="input-group">
                <input type="email" placeholder="Email" class="w-full input input-bordered" />
            </label>
        </div>

        <div class="form-control">
            <label class="input-group">
                <input type="username" placeholder="Username" class="w-full input input-bordered" />
            </label>
        </div>

        <div class="form-control">
            <label class="input-group">
                <input type="password" placeholder="Password" class="w-full input input-bordered" />
            </label>
        </div>

        <div class="text-center">
            <p class="text-sm">
                Have an account? 
                <a href="{{ route('login') }}" class="text-primary hover:underline">Login here</a>.
            </p>
        </div>

        <div class="form-control">
            <button class="w-full btn btn-primary">Register</button>
        </div>
    </div>
</div>
@endsection