<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Auth;

class AuthController extends Controller
{
    /**
     * Register a new user in the system.
     *
     * Validates the incoming request data for name, email, and password.
     * Creates a new user record in the database with the validated data.
     * Returns the newly created user as a JSON response.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json($user);
    }

    /**
     * Authenticate a user with the given email and password.
     *
     * Validates the incoming request data for email and password.
     * Queries the database for a user with the given email.
     * Checks that the given password matches the user's password
     * from the database using the Hash facade.
     * Logs the user into the application using the Auth facade.
     * Returns the authenticated user as a JSON response.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $user = User::where('email', $request->input('email'))->first();

        if (! $user || ! Hash::check($request->input('password'), $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Incorrect credentials.'],
            ]);
        }

        $token = $user->createToken('login_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    /**
     * Log out the authenticated user.
     *
     * Logs the user out of the application using the web guard
     * and returns a JSON response indicating the user has been logged out.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // auth()->guard('web')->logout();
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out']);
    }

    /**
     * Retrieve the authenticated user's information.
     *
     * Returns the currently authenticated user as a JSON response.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function me(Request $request)
    {
        return response()->json(Auth::user());
    }
}
