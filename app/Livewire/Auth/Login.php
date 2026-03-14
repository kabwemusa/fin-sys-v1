<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public string $identifier = '';   // email or phone
    public string $password   = '';

    public function login(): void
    {
        $this->validate([
            'identifier' => 'required|string',
            'password'   => 'required|string',
        ]);

        // Detect whether input looks like an email
        $isEmail = filter_var($this->identifier, FILTER_VALIDATE_EMAIL);

        if ($isEmail) {
            $credentials = ['email' => $this->identifier, 'password' => $this->password];
        } else {
            // Look up user by phone, then attempt with their email
            $user = User::where('phone', $this->identifier)->first();
            if (!$user) {
                session()->flash('error', 'No account found with that phone number.');
                $this->password = '';
                return;
            }
            $credentials = ['email' => $user->email, 'password' => $this->password];
        }

        if (Auth::attempt($credentials, true)) {
            session()->regenerate();
            $role = Auth::user()->role ?? 'customer';
            $this->redirect(
                $role === 'admin' ? route('admin.dashboard') : route('portal.loans'),
                navigate: true
            );
            return;
        }

        session()->flash('error', 'Incorrect password. Please try again.');
        $this->password = '';
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->layout('components.layouts.auth', ['title' => 'Sign In']);
    }
}
