<?php

namespace App\Livewire\Public;

use App\Models\LoanApplication;
use Livewire\Component;

class Confirmation extends Component
{
    public ?LoanApplication $application = null;

    public function mount(string $reference): void
    {
        $this->application = LoanApplication::where('reference', $reference)
            ->with('loanProduct')
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.public.confirmation')
            ->layout('components.layouts.app', ['title' => 'Application Submitted']);
    }
}
