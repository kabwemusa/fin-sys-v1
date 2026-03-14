<?php

namespace App\Livewire\Public;

use App\Models\LoanProduct;
use Livewire\Component;

class LoanProductDetail extends Component
{
    public ?LoanProduct $product = null;

    public function mount(string $slug): void
    {
        $this->product = LoanProduct::where('slug', $slug)->where('is_active', true)->firstOrFail();
    }

    public function render()
    {
        return view('livewire.public.loan-product-detail')
            ->layout('components.layouts.app', ['title' => $this->product->name]);
    }
}
