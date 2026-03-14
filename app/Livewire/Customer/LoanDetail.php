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

    public function mount(string $reference): void
    {
        $customer = Auth::user()->customer;

        $this->application = LoanApplication::where('reference', $reference)
            ->where('customer_id', $customer?->id)
            ->with(['loanProduct', 'documents', 'collaterals', 'repayments'])
            ->firstOrFail();
    }

    public function uploadDocument(): void
    {
        $this->validate([
            'extra_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $path = $this->extra_document->store(
            "documents/{$this->application->customer_id}/{$this->application->reference}",
            'local'
        );

        Document::create([
            'loan_application_id' => $this->application->id,
            'type'                => 'additional',
            'original_filename'   => $this->extra_document->getClientOriginalName(),
            'file_path'           => $path,
            'file_size'           => $this->extra_document->getSize(),
        ]);

        $this->extra_document = null;
        $this->application->refresh()->load('documents');
        session()->flash('success', 'Document uploaded successfully.');
    }

    public function render()
    {
        return view('livewire.customer.loan-detail')
            ->layout('components.layouts.portal', ['title' => $this->application->reference]);
    }
}
