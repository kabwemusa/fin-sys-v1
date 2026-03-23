<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TemporaryPasswordNotification extends Notification
{
    use Queueable;

    public function __construct(public readonly string $temporaryPassword) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Customer Portal Access — Temporary Password')
            ->greeting("Hello {$notifiable->name},")
            ->line('Your portal password has been reset by our team.')
            ->line("**Email:** {$notifiable->email}")
            ->line("**Temporary Password:** {$this->temporaryPassword}")
            ->line('Please sign in and change your password immediately.')
            ->action('Sign In to Portal', route('login'))
            ->line('If you did not request this, please contact support.');
    }
}
