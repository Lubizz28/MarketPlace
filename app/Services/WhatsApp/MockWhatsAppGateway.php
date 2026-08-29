<?php

namespace App\Services\WhatsApp;

use App\Contracts\WhatsAppGatewayInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MockWhatsAppGateway implements WhatsAppGatewayInterface
{
    /**
     * Send simulated WhatsApp message and log output.
     */
    public function sendMessage(string $phone, string $message): array
    {
        $normalizedPhone = $this->normalizePhone($phone);
        $messageId = 'WA-' . strtoupper(Str::random(12));

        Log::channel('single')->info("WhatsApp Message Dispatched to [{$normalizedPhone}]:\n{$message}");

        return [
            'success' => true,
            'message_id' => $messageId,
            'response' => [
                'recipient' => $normalizedPhone,
                'status' => 'sent',
                'timestamp' => now()->toIso8601String(),
            ],
        ];
    }

    protected function normalizePhone(string $phone): string
    {
        $cleaned = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($cleaned, '0')) {
            return '62' . substr($cleaned, 1);
        }

        if (str_starts_with($cleaned, '8')) {
            return '62' . $cleaned;
        }

        return $cleaned;
    }
}
