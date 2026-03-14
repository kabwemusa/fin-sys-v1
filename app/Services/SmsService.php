<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * Send SMS. Currently logs only — replace with actual SMS gateway integration.
     * TODO: Integrate with Zambian SMS gateway (e.g. Africa's Talking, Zamtel API)
     */
    public function send(string $phone, string $message): bool
    {
        Log::info("SMS to {$phone}: {$message}");
        return true;
    }
}
