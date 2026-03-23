<?php

namespace App\Livewire\Auth;

use Illuminate\Auth\Events\PasswordReset as PasswordResetEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

class PasswordReset extends Component
{
    public string $token  = '';
    public string $email  = '';
    public string $password = '';
    public string $password_confirmation = '';

    public bool $done = false;

    public function mount(string $token): void
    {
        $this->token = $token;
        $this->email = request()->query('email', '');
    }

    public function submit(): void
    {
        $this->validate([
            'token'                 => 'required',
            'email'                 => 'required|email',
            'password'              => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        $status = Password::reset(
            [
                'email'                 => $this->email,
                'password'              => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token'                 => $this->token,
            ],
            function ($user) {
                $user->forceFill([
                    'password'             => Hash::make($this->password),
                    'remember_token'       => Str::random(60),
                    'must_change_password' => false,
                ])->save();

                event(new PasswordResetEvent($user));
                Auth::login($user);
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            $this->done = true;
        } else {
            $this->addError('email', __($status));
        }
    }

    #[Layout('components.layouts.auth', ['title' => 'Reset Password'])]
    public function render()
    {
        return view('livewire.auth.password-reset');
    }
}
