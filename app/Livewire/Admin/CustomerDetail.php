<?php

namespace App\Livewire\Admin;

use App\Models\Customer;
use App\Notifications\TemporaryPasswordNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Livewire\Component;

class CustomerDetail extends Component
{
    public Customer $customer;

    public function mount(int $id): void
    {
        $this->customer = Customer::with(['user', 'loanApplications.loanProduct'])
            ->findOrFail($id);
    }

    /**
     * Generate a temporary password, save it, and email it to the customer.
     * Used when the customer never received (or lost) their welcome email.
     */
    public function resetPassword(): void
    {
        $plain = \Illuminate\Support\Str::random(10);

        $this->customer->user->update([
            'password'             => Hash::make($plain),
            'must_change_password' => true,
        ]);

        try {
            $this->customer->user->notify(new TemporaryPasswordNotification($plain));
            $this->dispatch('notify', type: 'success', message: 'Temporary password emailed to ' . $this->customer->user->email);
        } catch (\Throwable $e) {
            Log::error('Failed to email temporary password', [
                'customer_id' => $this->customer->id,
                'error'       => $e->getMessage(),
            ]);
            // Password was still reset — surface it on-screen as fallback
            $this->dispatch('notify', type: 'warning', message: "Mail failed. Temporary password: {$plain}");
        }
    }

    /**
     * Send a standard Laravel password-reset link to the customer's email.
     * Preferred when the customer already knows their email but can't log in.
     */
    public function sendPasswordResetLink(): void
    {
        $status = Password::sendResetLink(['email' => $this->customer->user->email]);

        if ($status === Password::RESET_LINK_SENT) {
            $this->dispatch('notify', type: 'success', message: 'Password reset link sent to ' . $this->customer->user->email);
        } else {
            $this->dispatch('notify', type: 'error', message: 'Could not send reset link. Check mail config or try again.');
        }
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
