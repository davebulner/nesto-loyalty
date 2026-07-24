<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Order;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create an active customer with points
        $activeCustomer = User::create([
            'name' => 'John Doe',
            'nic' => '199012345678',
            'mobile' => '0771234567',
            'password' => Hash::make('password123'),
            'is_active' => true,
            'points_balance' => 150,
        ]);

        // Add some dummy orders for the active customer
        Order::create([
            'user_id' => $activeCustomer->id,
            'invoice_number' => 'INV-1001',
            'transaction_date' => '2023-10-01',
            'branch' => 'Colombo 03',
            'amount' => 15000.00,
            'points_earned' => 150,
        ]);

        Order::create([
            'user_id' => $activeCustomer->id,
            'invoice_number' => 'INV-1002',
            'transaction_date' => '2023-10-05',
            'branch' => 'Kandy',
            'amount' => 5000.00,
            'points_earned' => 0, // No points because it's under 10,000
        ]);

        // 2. Create an inactive customer (for testing activation)
        User::create([
            'name' => 'Jane Smith',
            'nic' => '199587654321',
            'mobile' => '0719876543',
            'password' => null,
            'is_active' => false,
            'points_balance' => 0,
        ]);
    }
}
