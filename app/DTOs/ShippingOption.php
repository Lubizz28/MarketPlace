<?php

namespace App\DTOs;

class ShippingOption
{
    public function __construct(
        public string $courierCode,
        public string $courierName,
        public string $serviceCode,
        public string $serviceDescription,
        public int $cost,
        public string $etdDays,
    ) {}

    public function toArray(): array
    {
        return [
            'courier_code' => $this->courierCode,
            'courier_name' => $this->courierName,
            'service_code' => $this->serviceCode,
            'service_description' => $this->serviceDescription,
            'cost' => $this->cost,
            'formatted_cost' => 'Rp ' . number_format($this->cost, 0, ',', '.'),
            'etd_days' => $this->etdDays,
            'full_label' => strtoupper($this->courierCode) . ' ' . $this->serviceCode . ' (' . $this->etdDays . ' Hari) — Rp ' . number_format($this->cost, 0, ',', '.'),
        ];
    }
}
