<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function points(Request $request)
    {
        // Retrieve the customer's total loyalty points[cite: 1]
        return response()->json([
            'total_points' => $request->user()->points_balance
        ]);
    }

    public function orders(Request $request)
    {
        // Retrieve the customer's order history with earned points[cite: 1]
        // We order by transaction_date descending so the newest orders show up first
        $orders = $request->user()->orders()->orderBy('transaction_date', 'desc')->get();
        
        return response()->json([
            'orders' => $orders
        ]);
    }
}
