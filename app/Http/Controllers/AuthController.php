<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/products');
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    public function user(Request $request)
    {
        $user = Auth::user();
        return response()->json([
            'authenticated' => true,
            'user' => [
                'id' => $user->id,
                'isAdmin' => $user->role_id === 1,
                'username' => $user->username,
                'email' => $user->email,
            ]
        ]);
    }
    public function logout(Request $request)
    {
        auth()->guard('web')->logout();
        $request->session()->invalidate();

        return redirect('/');
    }
}
