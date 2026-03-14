<?php

namespace App\Notifications;

use App\Models\LoanApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewApplicationForAdmin extends Notification
{
    use Queueable;

    public function __construct(
        public readonly LoanApplication $application,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("New Loan Application — {$this->application->reference}")
            ->greeting("Hello {$notifiable->name},")
            ->line("New loan application **{$this->application->reference}** has been received.")
            ->line("Amount: ZMW {$this->application->amount_requested}")
            ->line("Type: {$this->application->loanProduct->name}")
            ->action('Review Application', route('admin.application.review', $this->application->id));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'reference' => $this->application->reference,
            'type' => 'new_application',
            'amount_requested' => $this->application->amount_requested,
            'loan_product' => $this->application->loanProduct->name,
        ];
    }
}
