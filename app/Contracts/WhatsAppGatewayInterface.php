<?php

namespace App\Contracts;

interface WhatsAppGatewayInterface
{
    /**
     * Send a WhatsApp message to the specified recipient phone number.
     *
     * @param string $phone Indonesian format phone number e.g. 08123456789 or 628123456789
     * @param string $message Formatted text message
     * @return array{success: bool, message_id: string|null, response: mixed}
     */
    public function sendMessage(string $phone, string $message): array;
}
