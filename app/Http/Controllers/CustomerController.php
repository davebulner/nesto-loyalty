<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function register(Request $request)
    {
        // 1. Manually create the validator
        $validator = Validator::make($request->all(), [
            'nic' => 'required|string|unique:users,nic',
            'mobile' => 'required|digits:10|unique:users,mobile',
            'name' => 'required|string',
        ], [
            // NIC Errors
            'nic.required' => 'The NIC number is required.',
            'nic.string' => 'The NIC format is invalid.',
            'nic.unique' => 'This NIC is already registered in the loyalty system.',

            // Mobile Errors
            'mobile.required' => 'The mobile number is required.',
            'mobile.digits' => 'Please enter exactly 10 digits for the mobile number.',
            'mobile.unique' => 'This mobile number is already linked to another account.',

            // Name Errors
            'name.required' => 'The customer name is required to proceed.',
            'name.string' => 'The customer name must be valid text.'
        ]);

        // 2. If validation fails, return just the FIRST error as a simple message
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 422);
        }

        // 3. Create the user using only the safely validated data
        $user = User::create($validator->validated());

        return response()->json([
            'message' => 'Customer registered successfully.',
            'customer' => $user
        ], 201);
    }

    public function activate(Request $request)
    {
        // 1. Manually create the validator
        $validator = Validator::make($request->all(), [
            'nic' => 'required|exists:users,nic',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'nic.required' => 'Please provide your registered NIC number.',
            'nic.exists' => 'This NIC is not registered in our system. Please visit a cashier first.',
            'password.required' => 'You must set a password.',
            'password.min' => 'Your password must be at least 8 characters long.',
            'password.confirmed' => 'The password confirmation does not match.'
        ]);

        // 2. If validation fails, return just a simple single message
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 422);
        }

        // 3. Proceed with activation if validation passes
        $user = User::where('nic', $request->nic)->first();

        if ($user->is_active) {
            return response()->json(['message' => 'Account is already active.'], 400);
        }

        $user->update([
            'password' => Hash::make($request->password),
            'is_active' => true
        ]);

        return response()->json(['message' => 'Account activated successfully.']);
    }
}
