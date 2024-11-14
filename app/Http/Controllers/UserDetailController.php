<?php

namespace App\Http\Controllers;

use App\Models\UserDetail;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\StoreUserDetailRequest;
use App\Http\Requests\UpdateUserDetailRequest;
use App\Http\Requests\UpdateUserPasswordRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;


class UserDetailController extends Controller
{
    // public function showChangePasswordForm()
    // {
    //     return view('auth.change-password');
    // }

    public function updatePassword(UpdateUserPasswordRequest $request)
    {
       
    
        try {
            DB::beginTransaction();
    
            // Get the authenticated user
            $user = Auth::user()->userDetail;
    
            if (!$this->isUsernameValid($request->username, $user->username)) {
                return response()->json([
                    'message' => 'Invalid username'
                ], 422);
            }
            // Verify current password
            if (!Hash::check($request->currentPassword, $user->password)) {
                return response()->json([
                    'error' => 'Current password is incorrect.'
                ], 422);
            }
            
            $user->password = $user['newPassword'];
            $user->save();
            DB::commit();
    
            return response()->json([
                'message' => 'Password updated successfully.',
                'status' => true
            ], 200);
        }
        catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Password update failed', 500);
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
    public function store(StoreUserDetailRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(UserDetail $userDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserDetail $userDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserDetailRequest $request, UserDetail $userDetail)
    {
        if (!Hash::check($request->currentPassword, Auth::user()->password)) {
            throw ValidationException::withMessages([
                'currentPassword' => ['The provided password does not match your current password.']
            ]);
        }

        // Get validated data
        $validated = $request->validated();
        
        
        // Remove currentPassword from validated data
        unset($validated['currentPassword']);
        
        // Update user details
        $user = User::find(Auth::id());
        $user->update($validated);

        return response()->json([
            'message' => 'User details updated successfully',
            'user' => $user
        ], 200);


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserDetail $userDetail)
    {
        //
    }
}
