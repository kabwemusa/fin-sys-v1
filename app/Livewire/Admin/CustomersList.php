<?php

namespace App\Livewire\Admin;

use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;

class CustomersList extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatedSearch(): void { $this->resetPage(); }

    public function render()
    {
        $customers = Customer::with('user')
            ->withCount('loanApplications')
            ->when($this->search, function ($q) {
                $q->whereHas('user', function ($u) {
                    $u->where('name',  'ilike', "%{$this->search}%")
                      ->orWhere('email', 'ilike', "%{$this->search}%")
                      ->orWhere('phone', 'ilike', "%{$this->search}%");
                })->orWhere('nrc_number', 'ilike', "%{$this->search}%");
            })
            ->latest()
            ->paginate(15);

        return view('livewire.admin.customers-list', compact('customers'))
            ->layout('components.layouts.admin', ['title' => 'Customers']);
    }
}
