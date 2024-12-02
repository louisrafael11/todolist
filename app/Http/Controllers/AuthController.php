<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Register a new user
    public function register(Request $request)
    {
        // Validate incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        // Create new user and hash password
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Return success message
        return response()->json(['message' => 'User registered successfully']);
    }

    // Log in an existing user
    public function login(Request $request)
    {
        // Get credentials from request
        $credentials = $request->only('email', 'password');

        // Attempt to authenticate
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Get authenticated user
        $user = Auth::user();

        // Return authenticated user data
        return response()->json($user);
    }

    // Log out the user
    public function logout()
    {
        Auth::logout();  // Log out the user

        // Return logout success message
        return response()->json(['message' => 'Logged out successfully']);
    }
}
