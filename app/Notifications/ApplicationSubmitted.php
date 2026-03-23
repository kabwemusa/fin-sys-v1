<?php

namespace App\Notifications;

use App\Models\LoanApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationSubmitted extends Notification
{
    use Queueable;

    public function __construct(
        public readonly LoanApplication $application,
        public readonly ?string $plainPassword = null,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $loginUrl = route('login');

        $mail = (new MailMessage)
            ->subject("Loan Application {$this->application->reference} Received")
            ->greeting("Hello {$notifiable->name},")
            ->line("Your loan application **{$this->application->reference}** has been received.");

        if ($this->plainPassword) {
            $mail
                ->line('Your customer portal account is ready.')
                ->line("Email: {$notifiable->email}")
                ->line("Password: {$this->plainPassword}")
                ->line('Please sign in and change your password after your first login.');
        } else {
            $mail
                ->line('Use your existing customer portal account to track the progress of this application.');
        }

        return $mail
            ->action('Sign In to Track Progress', $loginUrl)
            ->line('Keep your application reference safe in case you need support.');
    }

    public function smsMessage(object $notifiable): string
    {
        $loginUrl = route('login');

        if ($this->plainPassword) {
            return "Loan app {$this->application->reference} received. Login: {$notifiable->email} / {$this->plainPassword}. Sign in: {$loginUrl}";
        }

        return "Loan app {$this->application->reference} received. Sign in with your existing portal account: {$loginUrl}";
    }

    public function toArray(object $notifiable): array
    {
        return [
            'reference' => $this->application->reference,
            'type' => 'application_submitted',
        ];
    }
}
