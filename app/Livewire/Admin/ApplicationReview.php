<?php

namespace App\Livewire\Admin;

use App\Models\Document;
use App\Models\LoanApplication;
use App\Notifications\LoanStatusChanged;
use App\Services\LoanCalculatorService;
use App\Services\NotificationDeliveryService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ApplicationReview extends Component
{
    public LoanApplication $application;
    public array $documentNotes = [];

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
            'customer.user', 'loanProduct', 'documents.reviewedByUser', 'collaterals', 'repayments', 'reviewer',
        ])->findOrFail($id);

        $this->approveAmount = (string) $this->application->amount_requested;
        $this->approveRate   = (string) $this->application->interest_rate;
        $this->approveTenure = $this->application->tenure_months;
        $this->disburseDate  = now()->toDateString();
        $this->loadDocumentNotes();
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

        $customerNotified = $this->notifyCustomerStatusChange($old, 'approved');
        $this->refreshApplication();
        $this->dispatch('notify', type: $customerNotified ? 'success' : 'warning', message: $customerNotified ? 'Application approved.' : 'Application approved, but email delivery could not be confirmed.');
        $this->dispatch('portal-action-finished');
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

        $customerNotified = $this->notifyCustomerStatusChange($old, 'rejected');
        $this->refreshApplication();
        $this->dispatch('notify', type: $customerNotified ? 'success' : 'warning', message: $customerNotified ? 'Application rejected.' : 'Application rejected, but email delivery could not be confirmed.');
        $this->dispatch('portal-action-finished');
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

        $customerNotified = $this->notifyCustomerStatusChange($old, 'info_requested');
        $this->refreshApplication();
        $this->dispatch('notify', type: $customerNotified ? 'success' : 'warning', message: $customerNotified ? 'Info requested from customer.' : 'Info requested from customer, but email delivery could not be confirmed.');
        $this->dispatch('portal-action-finished');
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

        $customerNotified = $this->notifyCustomerStatusChange($old, 'disbursed');
        $this->refreshApplication();
        $this->dispatch('notify', type: $customerNotified ? 'success' : 'warning', message: $customerNotified ? 'Loan marked as disbursed.' : 'Loan marked as disbursed, but email delivery could not be confirmed.');
        $this->dispatch('portal-action-finished');
    }

    public function markUnderReview(): void
    {
        $old = $this->application->status;
        $this->application->update(['status' => 'under_review', 'reviewed_by' => Auth::id(), 'reviewed_at' => now()]);
        $customerNotified = $this->notifyCustomerStatusChange($old, 'under_review');
        $this->refreshApplication();
        $this->dispatch('notify', type: $customerNotified ? 'info' : 'warning', message: $customerNotified ? 'Application marked as under review.' : 'Application marked as under review, but email delivery could not be confirmed.');
    }

    public function approveDocument(int $docId): void
    {
        $document = $this->documentForUpdate($docId);

        $document->update([
            'status' => 'approved',
            'is_verified' => true,
            'verified_by' => Auth::id(),
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'review_notes' => $this->documentNotes[$docId] ?? null,
        ]);

        $this->syncApplicationDocumentState();
        $this->refreshApplication();
        $this->dispatch('notify', type: 'success', message: "{$document->label()} approved.");
    }

    public function requestDocumentResubmission(int $docId): void
    {
        $document = $this->documentForUpdate($docId);
        $note = trim($this->documentNotes[$docId] ?? '');
        $oldStatus = $this->application->status;

        if ($note === '') {
            $this->addError("documentNotes.$docId", 'Add a note explaining what the customer should correct.');
            return;
        }

        $document->update([
            'status' => 'resubmission_requested',
            'is_verified' => false,
            'verified_by' => null,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'review_notes' => $note,
        ]);

        $this->syncApplicationDocumentState();
        $customerNotified = true;

        if ($this->application->fresh()->status === 'info_requested' && $oldStatus !== 'info_requested') {
            $customerNotified = $this->notifyCustomerStatusChange($oldStatus, 'info_requested');
        }

        $this->refreshApplication();
        $this->dispatch('notify', type: 'warning', message: $customerNotified ? "Resubmission requested for {$document->label()}." : "Resubmission requested for {$document->label()}, but email delivery could not be confirmed.");
    }

    public function render()
    {
        return view('livewire.admin.application-review')
            ->layout('components.layouts.admin', ['title' => $this->application->reference]);
    }

    private function documentForUpdate(int $docId): Document
    {
        return Document::where('id', $docId)
            ->where('loan_application_id', $this->application->id)
            ->firstOrFail();
    }

    private function syncApplicationDocumentState(): void
    {
        $requests = $this->application->documents()
            ->where('status', 'resubmission_requested')
            ->get();

        if ($requests->isNotEmpty()) {
            $note = $requests
                ->map(fn (Document $document) => $document->label().': '.($document->review_notes ?: 'Please upload a clearer replacement.'))
                ->implode(PHP_EOL);

            $this->application->update([
                'status' => 'info_requested',
                'info_requested_note' => $note,
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
            ]);

            return;
        }

        if ($this->application->status === 'info_requested') {
            $this->application->update([
                'status' => 'under_review',
                'info_requested_note' => null,
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
            ]);
        }
    }

    private function refreshApplication(): void
    {
        $this->application->refresh()->load([
            'customer.user',
            'loanProduct',
            'documents.reviewedByUser',
            'collaterals',
            'repayments',
            'reviewer',
        ]);

        $this->loadDocumentNotes();
    }

    private function loadDocumentNotes(): void
    {
        $this->documentNotes = $this->application->documents
            ->mapWithKeys(fn (Document $document) => [$document->id => $document->review_notes ?? ''])
            ->all();
    }

    private function notifyCustomerStatusChange(string $oldStatus, string $newStatus): bool
    {
        $application = $this->application->fresh();
        $application?->loadMissing('customer.user');

        return app(NotificationDeliveryService::class)->send(
            $application->customer->user,
            new LoanStatusChanged($application, $oldStatus, $newStatus),
            [
                'application_id' => $application->id,
                'loan_reference' => $application->reference,
                'audience' => 'customer',
            ]
        );
    }
}
