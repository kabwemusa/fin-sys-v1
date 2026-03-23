<?php

namespace App\Livewire\Customer;

use App\Models\LoanApplication;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class LoanDetail extends Component
{
    use WithFileUploads;

    public LoanApplication $application;
    public $extra_document = null;
    public string $replacement_document_type = 'other';

    public function mount(string $reference): void
    {
        $customer = Auth::user()->customer;

        $this->application = LoanApplication::where('reference', $reference)
            ->where('customer_id', $customer?->id)
            ->with(['loanProduct', 'documents', 'collaterals', 'repayments'])
            ->firstOrFail();

        $this->replacement_document_type = array_key_first($this->requestedDocumentTypes()) ?? 'other';
    }

    public function uploadDocument(): void
    {
        $allowedTypes = collect($this->application->loanProduct->documentChecklist())
            ->pluck('type')
            ->push('other')
            ->all();

        $this->validate([
            'extra_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'replacement_document_type' => 'required|in:'.implode(',', $allowedTypes),
        ]);

        $path = $this->extra_document->store(
            "documents/{$this->application->customer_id}/{$this->application->reference}",
            'local'
        );

        $realPath = $this->extra_document->getRealPath();
        $fileSize = ($realPath && file_exists($realPath)) ? filesize($realPath) : 0;

        if ($this->replacement_document_type !== 'other') {
            Document::where('loan_application_id', $this->application->id)
                ->where('type', $this->replacement_document_type)
                ->where('status', 'resubmission_requested')
                ->update(['status' => 'replaced']);
        }

        Document::create([
            'loan_application_id' => $this->application->id,
            'type'                => $this->replacement_document_type,
            'original_filename'   => $this->extra_document->getClientOriginalName(),
            'file_path'           => $path,
            'file_size'           => $fileSize,
            'status'              => 'pending',
            'notes'               => $this->replacement_document_type === 'other'
                ? 'Additional supporting document uploaded by customer.'
                : 'Replacement document uploaded by customer.',
        ]);

        if (! $this->application->documents()->where('status', 'resubmission_requested')->exists()) {
            $this->application->update([
                'status' => 'under_review',
                'info_requested_note' => null,
            ]);
        }

        $this->extra_document = null;
        $this->application->refresh()->load(['loanProduct', 'documents', 'collaterals', 'repayments']);
        $this->replacement_document_type = array_key_first($this->requestedDocumentTypes()) ?? 'other';
        session()->flash('success', 'Document uploaded successfully.');
    }

    public function render()
    {
        return view('livewire.customer.loan-detail', [
            'documentChecklist' => $this->application->documentChecklist(),
            'requestedDocumentTypes' => $this->requestedDocumentTypes(),
        ])
            ->layout('components.layouts.portal', ['title' => $this->application->reference]);
    }

    private function requestedDocumentTypes(): array
    {
        return $this->application->outstandingDocumentRequests()
            ->mapWithKeys(fn (Document $document) => [$document->type => $document->label()])
            ->all();
    }
}
