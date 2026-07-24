<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Manually create the validator
        $validator = Validator::make($request->all(), [
            'nic' => 'required|string',
            'password' => 'required|string',
        ], [
            'nic.required' => 'Please provide your NIC number to log in.',
            'password.required' => 'Please provide your password.'
        ]);

        // Return single error message if validation fails
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = User::where('nic', $request->nic)->first();

        // Verify credentials
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid NIC or password.'], 401);
        }

        // Verify account is active
        if (!$user->is_active) {
            return response()->json(['message' => 'Your account is not activated. Please activate it first.'], 403);
        }

        // Generate the secure token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    public function logout(Request $request)
    {
        // Revoke the token that was used to authenticate the current request
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }
}
