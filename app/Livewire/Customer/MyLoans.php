<?php

namespace App\Livewire\Customer;

use App\Models\LoanApplication;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class MyLoans extends Component
{
    use WithPagination;

    public function render()
    {
        $customer = Auth::user()->customer;

        $applications = $customer
            ? LoanApplication::where('customer_id', $customer->id)
                ->with('loanProduct')
                ->latest()
                ->paginate(10)
            : collect();

        return view('livewire.customer.my-loans', compact('applications'))
            ->layout('components.layouts.portal', ['title' => 'My Loans']);
    }
}
