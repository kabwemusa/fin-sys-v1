<?php

namespace App\Livewire\Admin;

use App\Models\LoanApplication;
use App\Models\LoanProduct;
use Livewire\Component;
use Livewire\WithPagination;

class ApplicationsList extends Component
{
    use WithPagination;

    public string $search    = '';
    public string $status    = '';
    public string $productId = '';
    public string $sortBy    = 'created_at';
    public string $sortDir   = 'desc';

    public function updatedSearch():    void { $this->resetPage(); }
    public function updatedStatus():    void { $this->resetPage(); }
    public function updatedProductId(): void { $this->resetPage(); }

    public function sort(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy  = $column;
            $this->sortDir = 'desc';
        }
        $this->resetPage();
    }

    public function render()
    {
        $query = LoanApplication::with(['customer.user', 'loanProduct'])
            ->when($this->status,    fn($q) => $q->where('status', $this->status))
            ->when($this->productId, fn($q) => $q->where('loan_product_id', $this->productId))
            ->when($this->search, function ($q) {
                $q->where(function ($inner) {
                    $inner->where('reference', 'ilike', "%{$this->search}%")
                          ->orWhereHas('customer.user', fn($u) => $u->where('name', 'ilike', "%{$this->search}%"));
                });
            })
            ->orderBy($this->sortBy, $this->sortDir);

        return view('livewire.admin.applications-list', [
            'applications' => $query->paginate(15),
            'products'     => LoanProduct::orderBy('name')->get(),
        ])->layout('components.layouts.admin', ['title' => 'Applications']);
    }
}
