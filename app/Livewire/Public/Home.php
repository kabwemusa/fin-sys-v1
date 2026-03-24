<?php

namespace App\Livewire\Public;

use App\Models\LoanProduct;
use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        return view('livewire.public.home', [
            'products' => LoanProduct::active()->get(),
        ])->layout('components.layouts.app', ['title' => 'Credence Systems']);
    }
}
