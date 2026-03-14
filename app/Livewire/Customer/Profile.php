<?php

namespace App\Livewire\Customer;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Profile extends Component
{
    // Editable user fields
    public string $name     = '';
    public string $phone    = '';
    public string $email    = '';

    // Editable customer fields
    public string $residential_address = '';
    public string $city     = '';
    public string $province = '';

    // Read-only
    public string $nrc_number = '';

    public function mount(): void
    {
        $user     = Auth::user();
        $customer = $user->customer;

        $this->name  = $user->name;
        $this->phone = $user->phone ?? '';
        $this->email = $user->email;

        if ($customer) {
            $this->nrc_number          = $customer->nrc_number ?? '';
            $this->residential_address = $customer->residential_address ?? '';
            $this->city                = $customer->city ?? '';
            $this->province            = $customer->province ?? '';
        }
    }

    public function save(): void
    {
        $this->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'residential_address' => 'nullable|string|max:500',
            'city'     => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
        ]);

        $user = Auth::user();
        $user->update([
            'name'  => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
        ]);

        $customer = $user->customer;
        if ($customer) {
            $customer->update([
                'residential_address' => $this->residential_address,
                'city'                => $this->city,
                'province'            => $this->province,
            ]);
        }

        $this->dispatch('notify', type: 'success', message: 'Profile updated successfully.');
    }

    public function render()
    {
        return view('livewire.customer.profile', [
            'customer' => Auth::user()->customer,
        ])->layout('components.layouts.portal', ['title' => 'My Profile']);
    }
}
