<?php

namespace App\Notifications;

use App\Models\LoanApplication;
use App\Services\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoanStatusChanged extends Notification
{
    use Queueable;

    public function __construct(
        public readonly LoanApplication $application,
        public readonly string $oldStatus,
        public readonly string $newStatus,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        [$subject, $body, $smsBody] = $this->buildContent();

        app(SmsService::class)->send($notifiable->phone ?? '', $smsBody);

        $mail = (new MailMessage)
            ->subject($subject)
            ->greeting("Hello {$notifiable->name},")
            ->line($body);

        if (in_array($this->newStatus, ['approved', 'disbursed', 'info_requested'])) {
            $mail->action('View Application', route('portal.loan.detail', $this->application->reference));
        }

        return $mail;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'reference' => $this->application->reference,
            'type' => 'status_changed',
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
        ];
    }

    private function buildContent(): array
    {
        $ref = $this->application->reference;

        return match ($this->newStatus) {
            'under_review' => [
                "Application {$ref} Under Review",
                "Your application **{$ref}** is now being reviewed by our team.",
                "Your loan app {$ref} is under review.",
            ],
            'info_requested' => [
                "Action Required — Application {$ref}",
                "Additional information is required for **{$ref}**: {$this->application->info_requested_note}. Please log in to upload documents.",
                "Action needed for {$ref}: {$this->application->info_requested_note}",
            ],
            'approved' => [
                "Congratulations! Application {$ref} Approved",
                "Congratulations! **{$ref}** has been approved for ZMW {$this->application->amount_approved}. Monthly repayment: ZMW {$this->application->monthly_repayment}.",
                "Congrats! Loan {$ref} approved. ZMW {$this->application->amount_approved} @ ZMW {$this->application->monthly_repayment}/mo.",
            ],
            'rejected' => [
                "Application {$ref} Not Approved",
                "We regret that **{$ref}** was not approved. Reason: {$this->application->rejection_reason}.",
                "Loan {$ref} not approved. Reason: {$this->application->rejection_reason}",
            ],
            'disbursed' => [
                "Funds Disbursed — Application {$ref}",
                "ZMW {$this->application->amount_approved} has been disbursed to your account. First repayment due: {$this->application->due_date?->format('d M Y')}.",
                "ZMW {$this->application->amount_approved} disbursed for {$ref}. Due: {$this->application->due_date?->format('d M Y')}.",
            ],
            default => [
                "Application {$ref} Status Update",
                "Your application **{$ref}** status has been updated to: {$this->newStatus}.",
                "Loan {$ref} status: {$this->newStatus}.",
            ],
        };
    }
}
