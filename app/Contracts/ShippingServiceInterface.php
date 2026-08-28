<?php

namespace App\Contracts;

use App\DTOs\ShippingOption;

interface ShippingServiceInterface
{
    /**
     * Get list of supported provinces.
     *
     * @return array<int, array{id: string, name: string}>
     */
    public function getProvinces(): array;

    /**
     * Get list of cities in a given province.
     *
     * @return array<int, array{id: string, province_id: string, type: string, name: string, postal_code: string}>
     */
    public function getCities(string $provinceId): array;

    /**
     * Calculate shipping costs from warehouse origin to destination city.
     *
     * @param string $destinationCityId
     * @param int $weightGrams
     * @param array<string> $couriers
     * @return array<ShippingOption>
     */
    public function calculateCost(string $destinationCityId, int $weightGrams, array $couriers = ['jne', 'pos', 'tiki', 'sicepat', 'jnt']): array;

    /**
     * Track shipment with courier waybill / tracking resi.
     */
    public function trackWaybill(string $courier, string $waybillNumber): array;
}
