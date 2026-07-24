<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // Capture order information: Invoice number, Date, Branch, Amount
        $validated = $request->validate([
            'invoice_number' => 'required|string|unique:orders,invoice_number',
            'transaction_date' => 'required|date',
            'branch' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        $pointsEarned = 0;

        // Points calculation rule: Order amount must be >= 10,000 LKR
        if ($validated['amount'] >= 10000) {
            // For every 100 LKR, earn 1 point[cite: 1]
            $pointsEarned = floor($validated['amount'] / 100);
        }

        $user = $request->user();

        // Use a database transaction to ensure absolute data integrity
        DB::transaction(function () use ($validated, $pointsEarned, $user) {
            // Store customer order details[cite: 1]
            $user->orders()->create([
                'invoice_number' => $validated['invoice_number'],
                'transaction_date' => $validated['transaction_date'],
                'branch' => $validated['branch'],
                'amount' => $validated['amount'],
                'points_earned' => $pointsEarned,
            ]);

            // Add the earned points to the user's balance
            if ($pointsEarned > 0) {
                $user->increment('points_balance', $pointsEarned);
            }
        });

        return response()->json([
            'message' => 'Order captured successfully.',
            'points_earned' => $pointsEarned
        ], 201);
    }
}
