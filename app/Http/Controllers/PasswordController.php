<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PasswordController extends Controller
{
    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'new_password' => ['sometimes', 'string', 'min:8'],
            'currentPassword' => ['required', 'string'],
        ]);

        // cek password lama
        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Wrong password']);
        }

        // Update password
        $user = Auth::user();
        $user->password = Hash::make($request->new_password);

        return redirect()->back()->with('success', 'Your password has changed');
    }
}