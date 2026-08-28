<?php

namespace App\Services\Shipping;

use App\Contracts\ShippingServiceInterface;
use App\DTOs\ShippingOption;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RajaOngkirShippingService implements ShippingServiceInterface
{
    protected string $apiKey;
    protected string $baseUrl;
    protected string $originCityId;

    public function __construct()
    {
        $this->apiKey = config('services.rajaongkir.api_key', env('RAJAONGKIR_API_KEY', ''));
        $this->baseUrl = config('services.rajaongkir.base_url', 'https://api.rajaongkir.com/starter');
        $this->originCityId = config('services.rajaongkir.origin_city_id', env('RAJAONGKIR_ORIGIN_CITY_ID', '152')); // Default: Jakarta Pusat
    }

    public function getProvinces(): array
    {
        if (!empty($this->apiKey)) {
            try {
                $response = Http::withHeaders(['key' => $this->apiKey])->get("{$this->baseUrl}/province");
                if ($response->successful() && isset($response->json()['rajaongkir']['results'])) {
                    return array_map(function ($item) {
                        return [
                            'id' => (string) $item['province_id'],
                            'name' => (string) $item['province'],
                        ];
                    }, $response->json()['rajaongkir']['results']);
                }
            } catch (\Throwable $e) {
                Log::warning("RajaOngkir getProvinces failed: " . $e->getMessage());
            }
        }

        return $this->getMockProvinces();
    }

    public function getCities(string $provinceId): array
    {
        if (!empty($this->apiKey)) {
            try {
                $response = Http::withHeaders(['key' => $this->apiKey])->get("{$this->baseUrl}/city", [
                    'province' => $provinceId,
                ]);
                if ($response->successful() && isset($response->json()['rajaongkir']['results'])) {
                    return array_map(function ($item) {
                        return [
                            'id' => (string) $item['city_id'],
                            'province_id' => (string) $item['province_id'],
                            'type' => (string) $item['type'],
                            'name' => (string) $item['type'] . ' ' . $item['city_name'],
                            'postal_code' => (string) ($item['postal_code'] ?? ''),
                        ];
                    }, $response->json()['rajaongkir']['results']);
                }
            } catch (\Throwable $e) {
                Log::warning("RajaOngkir getCities failed: " . $e->getMessage());
            }
        }

        return $this->getMockCities($provinceId);
    }

    public function calculateCost(string $destinationCityId, int $weightGrams, array $couriers = ['jne', 'pos', 'tiki', 'sicepat', 'jnt']): array
    {
        // Enforce minimum weight 1000g (1 kg)
        $weight = max(1000, $weightGrams);
        $options = [];

        if (!empty($this->apiKey)) {
            foreach ($couriers as $courier) {
                try {
                    $response = Http::withHeaders(['key' => $this->apiKey])->post("{$this->baseUrl}/cost", [
                        'origin' => $this->originCityId,
                        'destination' => $destinationCityId,
                        'weight' => $weight,
                        'courier' => $courier,
                    ]);

                    if ($response->successful() && isset($response->json()['rajaongkir']['results'][0]['costs'])) {
                        $results = $response->json()['rajaongkir']['results'][0];
                        $courierCode = $results['code'];
                        $courierName = $results['name'];

                        foreach ($results['costs'] as $costItem) {
                            $serviceCode = $costItem['service'];
                            $serviceDesc = $costItem['description'] ?? $serviceCode;
                            $costValue = (int) ($costItem['cost'][0]['value'] ?? 0);
                            $etd = (string) ($costItem['cost'][0]['etd'] ?? '2-3');

                            $options[] = new ShippingOption(
                                courierCode: $courierCode,
                                courierName: $courierName,
                                serviceCode: $serviceCode,
                                serviceDescription: $serviceDesc,
                                cost: $costValue,
                                etdDays: $etd
                            );
                        }
                    }
                } catch (\Throwable $e) {
                    Log::warning("RajaOngkir calculateCost ({$courier}) failed: " . $e->getMessage());
                }
            }

            if (!empty($options)) {
                return $options;
            }
        }

        // Realistic Fallback Estimation based on weight (kg)
        return $this->getMockRates($destinationCityId, $weight);
    }

    public function trackWaybill(string $courier, string $waybillNumber): array
    {
        return [
            'waybill' => $waybillNumber,
            'courier' => strtoupper($courier),
            'status' => 'ON_PROCESS',
            'manifests' => [
                [
                    'city' => 'Jakarta Pusat Hub',
                    'date' => now()->subDay()->format('Y-m-d H:i:s'),
                    'description' => 'Paket telah diterima di sorting center pusat.',
                ],
                [
                    'city' => 'Transit Hub',
                    'date' => now()->format('Y-m-d H:i:s'),
                    'description' => 'Paket sedang dalam perjalanan menuju kota tujuan.',
                ],
            ],
        ];
    }

    protected function getMockRates(string $destinationCityId, int $weightGrams): array
    {
        $kg = ceil($weightGrams / 1000);

        return [
            new ShippingOption(
                courierCode: 'jne',
                courierName: 'Jalur Nugraha Ekakurir (JNE)',
                serviceCode: 'REG',
                serviceDescription: 'Layanan Reguler Nusantara',
                cost: (int) (18000 * $kg),
                etdDays: '2-3'
            ),
            new ShippingOption(
                courierCode: 'jne',
                courierName: 'Jalur Nugraha Ekakurir (JNE)',
                serviceCode: 'YES',
                serviceDescription: 'Yakin Esok Sampai (Express)',
                cost: (int) (32000 * $kg),
                etdDays: '1-1'
            ),
            new ShippingOption(
                courierCode: 'sicepat',
                courierName: 'SiCepat Ekspres',
                serviceCode: 'SIUNT',
                serviceDescription: 'SiUntung Kilat',
                cost: (int) (17000 * $kg),
                etdDays: '1-2'
            ),
            new ShippingOption(
                courierCode: 'jnt',
                courierName: 'J&T Express',
                serviceCode: 'EZ',
                serviceDescription: 'J&T Reguler Express',
                cost: (int) (19000 * $kg),
                etdDays: '2-3'
            ),
            new ShippingOption(
                courierCode: 'pos',
                courierName: 'POS Indonesia',
                serviceCode: 'KILAT_KHUSUS',
                serviceDescription: 'Pos Kilat Khusus',
                cost: (int) (15000 * $kg),
                etdDays: '2-4'
            ),
        ];
    }

    protected function getMockProvinces(): array
    {
        return [
            ['id' => '1', 'name' => 'DKI Jakarta'],
            ['id' => '2', 'name' => 'Jawa Barat'],
            ['id' => '3', 'name' => 'Jawa Tengah'],
            ['id' => '4', 'name' => 'DI Yogyakarta'],
            ['id' => '5', 'name' => 'Jawa Timur'],
            ['id' => '6', 'name' => 'Banten'],
            ['id' => '7', 'name' => 'Sumatera Utara'],
            ['id' => '8', 'name' => 'Sumatera Barat'],
            ['id' => '9', 'name' => 'Riau'],
            ['id' => '10', 'name' => 'Bali'],
        ];
    }

    protected function getMockCities(string $provinceId): array
    {
        $all = [
            '1' => [
                ['id' => '151', 'province_id' => '1', 'type' => 'Kota', 'name' => 'Kota Jakarta Barat', 'postal_code' => '11220'],
                ['id' => '152', 'province_id' => '1', 'type' => 'Kota', 'name' => 'Kota Jakarta Pusat', 'postal_code' => '10110'],
                ['id' => '153', 'province_id' => '1', 'type' => 'Kota', 'name' => 'Kota Jakarta Selatan', 'postal_code' => '12110'],
                ['id' => '154', 'province_id' => '1', 'type' => 'Kota', 'name' => 'Kota Jakarta Timur', 'postal_code' => '13330'],
                ['id' => '155', 'province_id' => '1', 'type' => 'Kota', 'name' => 'Kota Jakarta Utara', 'postal_code' => '14140'],
            ],
            '2' => [
                ['id' => '22', 'province_id' => '2', 'type' => 'Kota', 'name' => 'Kota Bandung', 'postal_code' => '40111'],
                ['id' => '23', 'province_id' => '2', 'type' => 'Kabupaten', 'name' => 'Kabupaten Bandung', 'postal_code' => '40311'],
                ['id' => '54', 'province_id' => '2', 'type' => 'Kota', 'name' => 'Kota Bekasi', 'postal_code' => '17121'],
                ['id' => '55', 'province_id' => '2', 'type' => 'Kabupaten', 'name' => 'Kabupaten Bekasi', 'postal_code' => '17530'],
                ['id' => '78', 'province_id' => '2', 'type' => 'Kota', 'name' => 'Kota Bogor', 'postal_code' => '16122'],
                ['id' => '79', 'province_id' => '2', 'type' => 'Kabupaten', 'name' => 'Kabupaten Bogor', 'postal_code' => '16911'],
                ['id' => '115', 'province_id' => '2', 'type' => 'Kota', 'name' => 'Kota Depok', 'postal_code' => '16411'],
            ],
            '6' => [
                ['id' => '455', 'province_id' => '6', 'type' => 'Kota', 'name' => 'Kota Tangerang', 'postal_code' => '15111'],
                ['id' => '456', 'province_id' => '6', 'type' => 'Kota', 'name' => 'Kota Tangerang Selatan', 'postal_code' => '15310'],
                ['id' => '457', 'province_id' => '6', 'type' => 'Kabupaten', 'name' => 'Kabupaten Tangerang', 'postal_code' => '15710'],
            ],
        ];

        return $all[$provinceId] ?? [
            ['id' => '100', 'province_id' => $provinceId, 'type' => 'Kota', 'name' => 'Kota Ibukota Provinsi', 'postal_code' => '50000'],
            ['id' => '101', 'province_id' => $provinceId, 'type' => 'Kabupaten', 'name' => 'Kabupaten Utama', 'postal_code' => '50111'],
        ];
    }
}
