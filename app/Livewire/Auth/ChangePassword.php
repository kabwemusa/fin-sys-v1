<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class ChangePassword extends Component
{
    public string $new_password              = '';
    public string $new_password_confirmation = '';

    public function save(): void
    {
        $this->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->password             = Hash::make($this->new_password);
        $user->must_change_password = false;
        $user->save();

        $role = $user->role ?? 'customer';
        $this->redirect(
            $role === 'admin' ? route('admin.dashboard') : route('portal.loans'),
            navigate: true
        );
    }

    public function render()
    {
        return view('livewire.auth.change-password')
            ->layout('components.layouts.auth', ['title' => 'Set Your Password']);
    }
}
