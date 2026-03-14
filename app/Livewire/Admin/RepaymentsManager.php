<?php

namespace App\Livewire\Admin;

use App\Models\LoanApplication;
use App\Models\Repayment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class RepaymentsManager extends Component
{
    use WithPagination;

    public string $activeTab = 'record';

    // Record form
    public string $loanReference  = '';
    public string $amount         = '';
    public string $payment_date   = '';
    public string $payment_method = 'bank_transfer';
    public string $reference_number = '';
    public string $notes          = '';

    public ?LoanApplication $foundLoan = null;

    public function mount(): void
    {
        $this->payment_date = now()->toDateString();
    }

    public function lookupLoan(): void
    {
        $this->foundLoan = LoanApplication::where('reference', strtoupper(trim($this->loanReference)))
            ->where('status', 'disbursed')
            ->with('customer.user')
            ->first();

        if (!$this->foundLoan) {
            $this->addError('loanReference', 'No active disbursed loan found with that reference.');
        }
    }

    public function recordRepayment(): void
    {
        $this->validate([
            'amount'         => 'required|numeric|min:0.01',
            'payment_date'   => 'required|date',
            'payment_method' => 'required|in:cash,bank_transfer,mobile_money,cheque',
        ]);

        if (!$this->foundLoan) {
            $this->addError('loanReference', 'Please look up a valid loan first.');
            return;
        }

        Repayment::create([
            'loan_application_id' => $this->foundLoan->id,
            'amount'              => $this->amount,
            'payment_date'        => $this->payment_date,
            'payment_method'      => $this->payment_method,
            'reference_number'    => $this->reference_number,
            'notes'               => $this->notes,
            'recorded_by'         => Auth::id(),
        ]);

        $this->reset(['amount', 'loanReference', 'reference_number', 'notes', 'foundLoan']);
        $this->payment_date = now()->toDateString();
        $this->dispatch('notify', type: 'success', message: 'Repayment recorded.');
    }

    public function render()
    {
        $recentRepayments = Repayment::with(['loanApplication.customer.user', 'recordedByUser'])
            ->latest()
            ->paginate(15);

        $overdueLoans = LoanApplication::where('status', 'disbursed')
            ->whereNotNull('due_date')
            ->where('due_date', '<', now()->toDateString())
            ->with('customer.user')
            ->get()
            ->filter(fn($a) => $a->outstandingBalance() > 0);

        return view('livewire.admin.repayments-manager', compact('recentRepayments', 'overdueLoans'))
            ->layout('components.layouts.admin', ['title' => 'Repayments']);
    }
}
