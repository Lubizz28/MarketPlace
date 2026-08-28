<?php

namespace App\DTOs;

use App\Enums\PaymentStatus;

class PaymentChargeResult
{
    public function __construct(
        public bool $success,
        public string $transactionId,
        public PaymentStatus $status,
        public ?string $snapToken = null,
        public ?string $redirectUrl = null,
        public ?string $vaNumber = null,
        public ?string $qrString = null,
        public array $rawResponse = [],
        public ?string $errorMessage = null,
    ) {}
}
