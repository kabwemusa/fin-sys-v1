<?php

namespace App\Notifications;

use App\Models\LoanApplication;
use App\Services\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationSubmitted extends Notification
{
    use Queueable;

    public function __construct(
        public readonly LoanApplication $application,
        public readonly string $plainPassword,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $portalUrl = route('portal.loans');

        app(SmsService::class)->send(
            $notifiable->phone ?? '',
            "Loan app {$this->application->reference} received. Login: {$notifiable->email} / {$this->plainPassword}. Portal: {$portalUrl}"
        );

        return (new MailMessage)
            ->subject("Loan Application {$this->application->reference} Received")
            ->greeting("Hello {$notifiable->name},")
            ->line("Your loan application **{$this->application->reference}** has been received.")
            ->line("**Your login credentials:**")
            ->line("Email: {$notifiable->email}")
            ->line("Password: {$this->plainPassword}")
            ->action('Track Your Application', $portalUrl)
            ->line('Please log in to track the progress of your application.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'reference' => $this->application->reference,
            'type' => 'application_submitted',
        ];
    }
}
