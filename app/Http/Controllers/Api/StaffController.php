<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // We use with('roles') to get the role name for each user
        return User::with('roles')->latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string',
        ]);

        // Create the user
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'], // Auto-hashed by the User model
        ]);

        // Assign the role using the permission package
        $user->assignRole($validatedData['role']);

        return response()->json($user->load('roles'), 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $staff)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($staff->id)],
            'password' => 'nullable|string|min:8', // Password is now optional
            'role' => 'required|string',
        ]);

        // Update main details
        $staff->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
        ]);
        
        // Only update password if a new one was provided
        if (!empty($validatedData['password'])) {
            $staff->password = $validatedData['password'];
            $staff->save();
        }

        // Use syncRoles to update the user's role
        $staff->syncRoles($validatedData['role']);

        return response()->json($staff->load('roles'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $staff)
    {
        // Prevent a user from deleting their own account
        if ($staff->id === auth()->id()) {
            return response()->json(['message' => 'You cannot delete your own account.'], 403);
        }
        
        $staff->delete();

        return response()->noContent();
    }
}