<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\StoreUserDetailRequest;
use App\Http\Requests\UpdatePurchaseCreditsRequest;
use App\Http\Requests\UpdateUserDetailRequest;
use Illuminate\Validation\ValidationException;

class UserDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->id == 1) {
            return response()->json([
                'userDetails' => User::with('userDetail')->get()
            ], 200);
        }
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
        try {
            DB::beginTransaction();

            if (!Hash::check($request->currentPassword, Auth::user()->password)) {
                throw ValidationException::withMessages([
                    'currentPassword' => ['The provided password does not match your current password.']
                ]);
            }
            $validated = $request->validated();

            $existingUsername = User::where('id', '!=', Auth::user()->id)
                ->where('username', $validated['username'])
                ->first();

            if ($existingUsername) {
                throw ValidationException::withMessages([
                    'username' => ['This username is already taken.']
                ]);
            }

            $existingEmail = User::where('id', '!=', Auth::user()->id)
                ->where('email', $validated['email'])
                ->first();


            if ($existingEmail) {
                throw ValidationException::withMessages([
                    'email' => ['This email is already taken.']
                ]);
            }

            unset($validated['currentPassword']);

            $user = User::findOrFail(Auth::user()->id);
            if (isset($validated['newPassword'])) {
                $validated['password'] = Hash::make($validated['newPassword']);
                unset($validated['newPassword']);
                unset($validated['newPasswordConfirm']);
            }
            
            $user->update($validated);
            
            DB::commit();

            return response()->json([
                'message' => 'User details updated successfully'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function updateAdmin(UpdateUserDetailRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            
            $validated = $request->validated();
            $user = User::findOrFail($id);
            $userDetail = $user->userDetail;

            $existingUsername = User::where('id', '!=', $id)
                ->where('username', $validated['username'])
                ->first();

            if ($existingUsername) {
                throw ValidationException::withMessages([
                    'username' => ['This username is already taken.']
                ]);
            }

            $existingEmail = User::where('id', '!=', $id)
                ->where('email', $validated['email'])
                ->first();
            
            if ($existingEmail) {
                throw ValidationException::withMessages([
                    'email' => ['This email is already taken.']
                ]);
            }

            $userFields = ['username', 'email'];
            $userUpdates = array_intersect_key($validated, array_flip($userFields));
            if (!empty($userUpdates)) {
                $user->update($userUpdates);
            }

            if (isset($validated['user_detail']['balance'])) {
                $userDetail->balance = $validated['user_detail']['balance'];
                $userDetail->save();
            }
            
            DB::commit();

            return response()->json([
                'message' => 'User details updated successfully'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'An error occurred while updating user details',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserDetail $userDetail)
    {
        //
    }

    /**
     * Show user's balance.
     */
    public function balance()
    {
        return response()->json([
            'balance' => Auth::user()->userDetail->balance
        ], 200);
    }

    /**
     * Purchase credits.
     */
    public function purchaseCredits(UpdatePurchaseCreditsRequest $request)
    {   
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $userDetail = UserDetail::where('user_id', $user->id)
                ->lockForUpdate()
                ->firstOrFail();

            $response = Http::timeout(30)
                ->post('http://localhost:5001/api/charge', [
                    'amount' => $request->amount
                ]);

            if ($response->failed()) {
                throw new \Exception('Payment failed: ' . $response->json('message', 'Error with payment gateway'));
            }

            $userDetail->balance += $request->amount;
            $userDetail->save();

            DB::commit();

            return response()->json(['balance' => $userDetail->balance], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
