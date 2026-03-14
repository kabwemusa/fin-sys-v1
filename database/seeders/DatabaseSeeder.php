<?php

namespace Database\Seeders;

use App\Models\LoanProduct;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@loansystem.com'],
            [
                'name' => 'System Admin',
                'phone' => '+260970000000',
                'password' => Hash::make('admin123456'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        LoanProduct::firstOrCreate(
            ['slug' => 'salary-backed-loan'],
            [
                'name' => 'Salary-Backed Loan',
                'type' => 'salary_backed',
                'description' => 'Affordable loans secured against your verified employment and salary. Ideal for civil servants and formal sector employees.',
                'min_amount' => 500.00,
                'max_amount' => 250000.00,
                'min_tenure_months' => 3,
                'max_tenure_months' => 60,
                'interest_rate' => 4.00,
                'requires_collateral' => false,
                'is_active' => true,
            ]
        );

        LoanProduct::firstOrCreate(
            ['slug' => 'collateral-backed-loan'],
            [
                'name' => 'Collateral-Backed Loan',
                'type' => 'collateral_backed',
                'description' => 'Larger loan amounts secured against your assets such as vehicles, property, or equipment.',
                'min_amount' => 5000.00,
                'max_amount' => 500000.00,
                'min_tenure_months' => 6,
                'max_tenure_months' => 60,
                'interest_rate' => 3.50,
                'requires_collateral' => true,
                'is_active' => true,
            ]
        );
    }
}
