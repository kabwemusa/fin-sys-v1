<?php

namespace App\Livewire\Public;

use App\Models\LoanProduct;
use Livewire\Component;

class LoanCalculator extends Component
{
    public string $theme = 'light';

    public function render()
    {
        $products = LoanProduct::active()->get();

        $productsJson = $products->map(fn($p) => [
            'id'            => $p->id,
            'name'          => $p->name,
            'slug'          => $p->slug,
            'apply_url'     => route('loan.apply', $p->slug),
            'interest_rate' => (float) $p->interest_rate,
            'min_amount'    => (float) $p->min_amount,
            'max_amount'    => (float) $p->max_amount,
            'min_tenure'    => (int)   $p->min_tenure_months,
            'max_tenure'    => (int)   $p->max_tenure_months,
        ])->values()->toJson();

        return view('livewire.public.loan-calculator', compact('products', 'productsJson'));
    }
}
