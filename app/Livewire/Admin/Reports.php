<?php

namespace App\Livewire\Admin;

use App\Models\LoanApplication;
use App\Models\Repayment;
use Livewire\Component;

class Reports extends Component
{
    public string $reportType = 'disbursement';
    public string $dateFrom   = '';
    public string $dateTo     = '';

    public function mount(): void
    {
        $this->dateFrom = now()->startOfMonth()->toDateString();
        $this->dateTo   = now()->toDateString();
    }

    public function render()
    {
        $data    = collect();
        $summary = [];

        if ($this->reportType === 'disbursement') {
            $data = LoanApplication::with('customer.user', 'loanProduct')
                ->where('status', 'disbursed')
                ->whereBetween('disbursed_at', [$this->dateFrom, $this->dateTo])
                ->latest('disbursed_at')
                ->get();
            $summary = ['count' => $data->count(), 'total' => $data->sum('amount_approved')];

        } elseif ($this->reportType === 'repayment') {
            $data = Repayment::with(['loanApplication.customer.user'])
                ->whereBetween('payment_date', [$this->dateFrom, $this->dateTo])
                ->latest('payment_date')
                ->get();
            $summary = ['count' => $data->count(), 'total' => $data->sum('amount')];

        } elseif ($this->reportType === 'overdue') {
            $data = LoanApplication::with('customer.user', 'loanProduct')
                ->where('status', 'disbursed')
                ->whereNotNull('due_date')
                ->where('due_date', '<', now()->toDateString())
                ->latest('due_date')
                ->get()
                ->filter(fn($a) => $a->outstandingBalance() > 0);
            $summary = ['count' => $data->count(), 'total' => $data->sum(fn($a) => $a->outstandingBalance())];

        } elseif ($this->reportType === 'summary') {
            $data = LoanApplication::selectRaw('status, COUNT(*) as count, SUM(amount_requested) as total_requested')
                ->whereBetween('created_at', [$this->dateFrom, $this->dateTo])
                ->groupBy('status')
                ->get();
            $summary = ['count' => $data->sum('count'), 'total' => $data->sum('total_requested')];
        }

        return view('livewire.admin.reports', compact('data', 'summary'))
            ->layout('components.layouts.admin', ['title' => 'Reports']);
    }
}
