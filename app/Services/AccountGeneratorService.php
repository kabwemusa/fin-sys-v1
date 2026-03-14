<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AccountGeneratorService
{
    /**
     * Create user account and customer profile from application data.
     *
     * @param array $personalData   Keys: name, email, phone, nrc_number, date_of_birth, gender, marital_status, residential_address, city, province
     * @param array $employmentData Keys: employer_name, employer_address, job_title, employment_date, monthly_income
     * @param array $bankingData    Keys: bank_name, bank_account_number, bank_branch
     * @return array{user: User, customer: Customer, plain_password: string|null}
     */
    public function createFromApplication(array $personalData, array $employmentData, array $bankingData): array
    {
        $existingUser = User::where('email', $personalData['email'])
            ->orWhere('phone', $personalData['phone'])
            ->first();

        if ($existingUser && $existingUser->customer) {
            return [
                'user' => $existingUser,
                'customer' => $existingUser->customer,
                'plain_password' => null,
            ];
        }

        $plainPassword = Str::random(10);

        $user = User::create([
            'name' => $personalData['name'],
            'email' => $personalData['email'],
            'phone' => $personalData['phone'],
            'password' => Hash::make($plainPassword),
            'role' => 'customer',
            'must_change_password' => true,
        ]);

        $customer = Customer::create([
            'user_id' => $user->id,
            'nrc_number' => $personalData['nrc_number'],
            'date_of_birth' => $personalData['date_of_birth'],
            'gender' => $personalData['gender'],
            'marital_status' => $personalData['marital_status'],
            'residential_address' => $personalData['residential_address'],
            'city' => $personalData['city'],
            'province' => $personalData['province'],
            'employer_name' => $employmentData['employer_name'] ?? null,
            'employer_address' => $employmentData['employer_address'] ?? null,
            'job_title' => $employmentData['job_title'] ?? null,
            'employment_date' => $employmentData['employment_date'] ?? null,
            'monthly_income' => $employmentData['monthly_income'] ?? null,
            'bank_name' => $bankingData['bank_name'] ?? null,
            'bank_account_number' => $bankingData['bank_account_number'] ?? null,
            'bank_branch' => $bankingData['bank_branch'] ?? null,
        ]);

        return [
            'user' => $user,
            'customer' => $customer,
            'plain_password' => $plainPassword,
        ];
    }
}
