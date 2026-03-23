<?php

namespace App\Services;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class NotificationDeliveryService
{
    private static bool $mailConfigurationLogged = false;

    public function __construct(
        private readonly SmsService $smsService,
    ) {}

    public function send(object $notifiable, Notification $notification, array $context = []): bool
    {
        $mailDiagnostics = $this->mailDiagnostics();
        $baseContext = array_merge($context, [
            'notification' => $notification::class,
            'recipient_email' => trim((string) data_get($notifiable, 'email')),
            'recipient_phone' => trim((string) data_get($notifiable, 'phone')),
            'mailer' => $mailDiagnostics['mailer'],
            'transport' => $mailDiagnostics['transport'],
        ]);

        $this->logMailConfigurationWarnings($mailDiagnostics);

        $mailDelivered = $this->sendMailNotification($notifiable, $notification, $baseContext);
        $this->sendSmsIfSupported($notifiable, $notification, $baseContext);

        return $mailDelivered && ! $this->usesLogOnlyTransport($mailDiagnostics);
    }

    private function sendMailNotification(object $notifiable, Notification $notification, array $context): bool
    {
        try {
            $notifiable->notify($notification);

            Log::info('Notification mail send completed.', $context);

            return true;
        } catch (Throwable $exception) {
            Log::error('Notification mail send failed.', array_merge($context, [
                'exception' => $exception::class,
                'error' => $exception->getMessage(),
            ]));

            return false;
        }
    }

    private function sendSmsIfSupported(object $notifiable, Notification $notification, array $context): void
    {
        if (! method_exists($notification, 'smsMessage')) {
            return;
        }

        $phone = trim((string) data_get($notifiable, 'phone'));
        $message = trim((string) $notification->smsMessage($notifiable));

        if ($message === '') {
            return;
        }

        if ($phone === '') {
            Log::warning('Notification SMS skipped because the recipient has no phone number.', $context);
            return;
        }

        try {
            $this->smsService->send($phone, $message);

            Log::info('Notification SMS handoff completed.', $context);
        } catch (Throwable $exception) {
            Log::error('Notification SMS dispatch failed.', array_merge($context, [
                'exception' => $exception::class,
                'error' => $exception->getMessage(),
            ]));
        }
    }

    private function mailDiagnostics(): array
    {
        $mailer = (string) config('mail.default', 'log');
        $transport = 'unavailable';

        try {
            $transport = (string) Mail::mailer($mailer)->getSymfonyTransport();
        } catch (Throwable $exception) {
            Log::warning('Unable to inspect the configured mail transport.', [
                'mailer' => $mailer,
                'exception' => $exception::class,
                'error' => $exception->getMessage(),
            ]);
        }

        return [
            'mailer' => $mailer,
            'transport' => $transport,
            'from_address' => (string) config('mail.from.address', ''),
            'smtp_host' => (string) config('mail.mailers.smtp.host', ''),
            'smtp_port' => (string) config('mail.mailers.smtp.port', ''),
            'local_domain' => (string) config('mail.mailers.smtp.local_domain', ''),
        ];
    }

    private function logMailConfigurationWarnings(array $mailDiagnostics): void
    {
        if (self::$mailConfigurationLogged) {
            return;
        }

        $warnings = [];

        if ($this->usesLogOnlyTransport($mailDiagnostics)) {
            $warnings[] = 'The active mail transport is logging messages instead of delivering them externally.';
        }

        if ($mailDiagnostics['from_address'] === '' || $mailDiagnostics['from_address'] === 'hello@example.com') {
            $warnings[] = 'The configured from address still looks like a placeholder value.';
        }

        if ($mailDiagnostics['smtp_host'] === '' && str_contains($mailDiagnostics['transport'], 'smtp')) {
            $warnings[] = 'SMTP transport is active but the configured SMTP host is blank.';
        }

        if (in_array($mailDiagnostics['local_domain'], ['localhost', '127.0.0.1'], true)) {
            $warnings[] = 'SMTP EHLO is using localhost. Set APP_URL or MAIL_EHLO_DOMAIN to a real host for better deliverability.';
        }

        if ($warnings === []) {
            Log::info('Mail transport diagnostics look healthy.', $mailDiagnostics);
        } else {
            Log::warning('Mail transport diagnostics need attention.', array_merge($mailDiagnostics, [
                'warnings' => $warnings,
            ]));
        }

        self::$mailConfigurationLogged = true;
    }

    private function usesLogOnlyTransport(array $mailDiagnostics): bool
    {
        return in_array($mailDiagnostics['mailer'], ['log', 'array'], true)
            || in_array($mailDiagnostics['transport'], ['log', 'array'], true);
    }
}
