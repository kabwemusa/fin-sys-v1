<?php

namespace App\Livewire\Admin;

use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class CustomerDetail extends Component
{
    public Customer $customer;

    public function mount(int $id): void
    {
        $this->customer = Customer::with(['user', 'loanApplications.loanProduct'])
            ->findOrFail($id);
    }

    public function resetPassword(): void
    {
        $plain = \Illuminate\Support\Str::random(10);
        $this->customer->user->update([
            'password'             => Hash::make($plain),
            'must_change_password' => true,
        ]);

        // In production you'd email the new password; for now flash it
        session()->flash('reset_password', $plain);
        $this->dispatch('notify', type: 'warning', message: "Password reset. Temporary: {$plain}");
    }

    public function render()
    {
        $totalBorrowed    = $this->customer->loanApplications->whereIn('status', ['approved','disbursed','closed'])->sum('amount_approved');
        $totalRepaid      = $this->customer->loanApplications->sum(fn($a) => $a->totalRepaid());
        $totalOutstanding = $this->customer->loanApplications->sum(fn($a) => $a->outstandingBalance());

        return view('livewire.admin.customer-detail', compact('totalBorrowed', 'totalRepaid', 'totalOutstanding'))
            ->layout('components.layouts.admin', ['title' => $this->customer->user->name]);
    }
}
