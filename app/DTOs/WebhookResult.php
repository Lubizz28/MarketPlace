<?php

namespace App\DTOs;

use App\Enums\PaymentStatus;

class WebhookResult
{
    public function __construct(
        public bool $isValid,
        public string $orderNumber,
        public string $transactionId,
        public PaymentStatus $paymentStatus,
        public int $grossAmount,
        public ?string $paymentType = null,
        public ?string $signatureKey = null,
        public array $rawPayload = [],
        public ?string $errorMessage = null,
    ) {}
}
