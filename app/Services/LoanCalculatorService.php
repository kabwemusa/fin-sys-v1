<?php

namespace App\Services;

class LoanCalculatorService
{
    /**
     * Calculate monthly repayment using flat interest rate.
     *
     * @param float $principal     Loan amount in ZMW
     * @param float $monthlyRate   Monthly interest rate as percentage (e.g. 4.0 = 4%)
     * @param int   $tenureMonths  Number of months
     * @return array{monthly_repayment: float, total_interest: float, total_repayment: float}
     */
    public function calculate(float $principal, float $monthlyRate, int $tenureMonths): array
    {
        $totalInterest = $principal * ($monthlyRate / 100) * $tenureMonths;
        $totalRepayment = $principal + $totalInterest;
        $monthlyRepayment = $totalRepayment / $tenureMonths;

        return [
            'monthly_repayment' => round($monthlyRepayment, 2),
            'total_interest' => round($totalInterest, 2),
            'total_repayment' => round($totalRepayment, 2),
        ];
    }
}
