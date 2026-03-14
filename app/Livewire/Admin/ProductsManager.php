<?php

namespace App\Livewire\Admin;

use App\Models\LoanProduct;
use Illuminate\Support\Str;
use Livewire\Component;

class ProductsManager extends Component
{
    public bool $showForm = false;
    public ?int $editingId = null;

    // Form fields
    public string $name        = '';
    public string $type        = 'salary_backed';
    public string $description = '';
    public string $min_amount  = '';
    public string $max_amount  = '';
    public int    $min_tenure  = 3;
    public int    $max_tenure  = 60;
    public string $interest_rate       = '';
    public bool   $requires_collateral = false;
    public bool   $is_active           = true;

    public function openCreate(): void
    {
        $this->reset(['editingId','name','type','description','min_amount','max_amount','min_tenure','max_tenure','interest_rate','requires_collateral','is_active']);
        $this->type       = 'salary_backed';
        $this->is_active  = true;
        $this->min_tenure = 3;
        $this->max_tenure = 60;
        $this->showForm   = true;
    }

    public function openEdit(int $id): void
    {
        $p = LoanProduct::findOrFail($id);
        $this->editingId          = $id;
        $this->name               = $p->name;
        $this->type               = $p->type;
        $this->description        = $p->description ?? '';
        $this->min_amount         = (string) $p->min_amount;
        $this->max_amount         = (string) $p->max_amount;
        $this->min_tenure         = $p->min_tenure_months;
        $this->max_tenure         = $p->max_tenure_months;
        $this->interest_rate      = (string) $p->interest_rate;
        $this->requires_collateral = $p->requires_collateral;
        $this->is_active          = $p->is_active;
        $this->showForm           = true;
    }

    public function save(): void
    {
        $this->validate([
            'name'          => 'required|string|max:255',
            'type'          => 'required|in:salary_backed,collateral_backed',
            'min_amount'    => 'required|numeric|min:1',
            'max_amount'    => 'required|numeric|gt:min_amount',
            'min_tenure'    => 'required|integer|min:1',
            'max_tenure'    => 'required|integer|gte:min_tenure',
            'interest_rate' => 'required|numeric|min:0',
        ]);

        $data = [
            'name'               => $this->name,
            'slug'               => Str::slug($this->name),
            'type'               => $this->type,
            'description'        => $this->description,
            'min_amount'         => $this->min_amount,
            'max_amount'         => $this->max_amount,
            'min_tenure_months'  => $this->min_tenure,
            'max_tenure_months'  => $this->max_tenure,
            'interest_rate'      => $this->interest_rate,
            'requires_collateral'=> $this->requires_collateral,
            'is_active'          => $this->is_active,
        ];

        if ($this->editingId) {
            LoanProduct::findOrFail($this->editingId)->update($data);
            $this->dispatch('notify', type: 'success', message: 'Product updated.');
        } else {
            LoanProduct::create($data);
            $this->dispatch('notify', type: 'success', message: 'Product created.');
        }

        $this->showForm = false;
    }

    public function toggleActive(int $id): void
    {
        $product = LoanProduct::findOrFail($id);
        $product->update(['is_active' => !$product->is_active]);
    }

    public function render()
    {
        return view('livewire.admin.products-manager', [
            'products' => LoanProduct::withCount('loanApplications')->latest()->get(),
        ])->layout('components.layouts.admin', ['title' => 'Loan Products']);
    }
}
