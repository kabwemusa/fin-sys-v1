<?php

namespace App\Livewire\Admin;

use App\Models\Document;
use App\Models\LoanApplication;
use App\Notifications\LoanStatusChanged;
use App\Services\LoanCalculatorService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ApplicationReview extends Component
{
    public LoanApplication $application;

    // Approve modal fields
    public string $approveAmount    = '';
    public string $approveRate      = '';
    public int    $approveTenure    = 12;
    public string $approveNotes     = '';

    // Reject modal fields
    public string $rejectReason = '';
    public string $rejectNotes  = '';

    // Info request modal
    public string $infoNote = '';

    // Disburse modal
    public string $disburseDate  = '';
    public string $disburseNotes = '';

    public function mount(int $id): void
    {
        $this->application = LoanApplication::with([
            'customer.user', 'loanProduct', 'documents', 'collaterals', 'repayments', 'reviewer',
        ])->findOrFail($id);

        $this->approveAmount = (string) $this->application->amount_requested;
        $this->approveRate   = (string) $this->application->interest_rate;
        $this->approveTenure = $this->application->tenure_months;
        $this->disburseDate  = now()->toDateString();
    }

    public function approve(): void
    {
        $this->validate([
            'approveAmount' => 'required|numeric|min:1',
            'approveRate'   => 'required|numeric|min:0',
            'approveTenure' => 'required|integer|min:1',
        ]);

        $calc = app(LoanCalculatorService::class)->calculate(
            (float) $this->approveAmount,
            (float) $this->approveRate,
            $this->approveTenure
        );

        $old = $this->application->status;
        $this->application->update([
            'status'            => 'approved',
            'amount_approved'   => $this->approveAmount,
            'interest_rate'     => $this->approveRate,
            'tenure_months'     => $this->approveTenure,
            'monthly_repayment' => $calc['monthly_repayment'],
            'admin_notes'       => $this->approveNotes,
            'reviewed_by'       => Auth::id(),
            'reviewed_at'       => now(),
        ]);

        $this->application->customer->user->notify(new LoanStatusChanged($this->application, $old, 'approved'));
        $this->application->refresh()->load(['customer.user', 'loanProduct', 'documents', 'collaterals', 'repayments']);
        $this->dispatch('notify', type: 'success', message: 'Application approved.');
    }

    public function reject(): void
    {
        $this->validate(['rejectReason' => 'required|string|min:10']);

        $old = $this->application->status;
        $this->application->update([
            'status'           => 'rejected',
            'rejection_reason' => $this->rejectReason,
            'admin_notes'      => $this->rejectNotes,
            'reviewed_by'      => Auth::id(),
            'reviewed_at'      => now(),
        ]);

        $this->application->customer->user->notify(new LoanStatusChanged($this->application, $old, 'rejected'));
        $this->application->refresh();
        $this->dispatch('notify', type: 'success', message: 'Application rejected.');
    }

    public function requestInfo(): void
    {
        $this->validate(['infoNote' => 'required|string|min:10']);

        $old = $this->application->status;
        $this->application->update([
            'status'              => 'info_requested',
            'info_requested_note' => $this->infoNote,
            'reviewed_by'         => Auth::id(),
            'reviewed_at'         => now(),
        ]);

        $this->application->customer->user->notify(new LoanStatusChanged($this->application, $old, 'info_requested'));
        $this->application->refresh();
        $this->dispatch('notify', type: 'success', message: 'Info requested from customer.');
    }

    public function disburse(): void
    {
        $this->validate(['disburseDate' => 'required|date']);

        $disbursedAt = \Carbon\Carbon::parse($this->disburseDate);
        $dueDate     = $disbursedAt->copy()->addMonths($this->application->tenure_months);

        $old = $this->application->status;
        $this->application->update([
            'status'       => 'disbursed',
            'disbursed_at' => $disbursedAt,
            'due_date'     => $dueDate,
            'admin_notes'  => $this->disburseNotes ?: $this->application->admin_notes,
        ]);

        $this->application->customer->user->notify(new LoanStatusChanged($this->application, $old, 'disbursed'));
        $this->application->refresh();
        $this->dispatch('notify', type: 'success', message: 'Loan marked as disbursed.');
    }

    public function markUnderReview(): void
    {
        $old = $this->application->status;
        $this->application->update(['status' => 'under_review', 'reviewed_by' => Auth::id(), 'reviewed_at' => now()]);
        $this->application->customer->user->notify(new LoanStatusChanged($this->application, $old, 'under_review'));
        $this->application->refresh();
        $this->dispatch('notify', type: 'info', message: 'Application marked as under review.');
    }

    public function verifyDocument(int $docId): void
    {
        Document::where('id', $docId)
            ->where('loan_application_id', $this->application->id)
            ->update(['is_verified' => true, 'verified_by' => Auth::id()]);

        $this->application->refresh()->load('documents');
    }

    public function render()
    {
        return view('livewire.admin.application-review')
            ->layout('components.layouts.admin', ['title' => $this->application->reference]);
    }
}
