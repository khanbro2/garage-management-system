<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\Vehicle;
use App\Models\MotRecord;

class MotApiService
{
    protected $baseUrl;
    protected $apiKey;
    protected $cacheDuration = 3600;

    public function __construct()
    {
        $this->baseUrl = config('services.mot.api_url', 'https://api.mot.gov.uk/v1');
        $this->apiKey = config('services.mot.api_key');
    }

    public function checkMotHistory(string $registrationNumber): ?array
    {
        $registration = $this->normalizeRegistration($registrationNumber);
        
        $cacheKey = "mot_history_{$registration}";
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'x-api-key' => $this->apiKey,
            ])->get("{$this->baseUrl}/mot-history/{$registration}");

            if ($response->successful()) {
                $data = $response->json();
                Cache::put($cacheKey, $data, $this->cacheDuration);
                return $data;
            }

            if ($response->status() === 404) {
                return null;
            }

            throw new \Exception("MOT API Error: {$response->status()}");
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }

    public function validateRegistration(string $registration): bool
    {
        $pattern = '/^[A-Z]{2}[0-9]{2}\s?[A-Z]{3}$|^[A-Z][0-9]{1,3}\s?[A-Z]{3}$|^[A-Z]{3}\s?[0-9]{1,3}[A-Z]$|^[0-9]{1,4}\s?[A-Z]{1,2}$|^[A-Z]{1,2}\s?[0-9]{1,4}$/i';
        return preg_match($pattern, trim($registration)) === 1;
    }

    public function normalizeRegistration(string $registration): string
    {
        return strtoupper(preg_replace('/\s+/', '', trim($registration)));
    }

    public function saveMotData(Vehicle $vehicle, array $motData): void
    {
        $vehicle->update([
            'make' => $motData['make'] ?? $vehicle->make,
            'model' => $motData['model'] ?? $vehicle->model,
            'year' => $motData['year'] ?? $vehicle->year,
            'mot_expiry' => $motData['motExpiryDate'] ?? null,
            'mileage' => $motData['odometerReading'] ?? $vehicle->mileage,
            'last_mot_check' => now(),
        ]);

        if (!empty($motData['motTests'])) {
            foreach ($motData['motTests'] as $test) {
                MotRecord::updateOrCreate(
                    [
                        'vehicle_id' => $vehicle->id,
                        'mot_test_number' => $test['motTestNumber'],
                    ],
                    [
                        'test_date' => $test['completedDate'],
                        'result' => strtolower($test['testResult']),
                        'mileage' => $test['odometerReading'] ?? null,
                        'expiry_date' => $test['expiryDate'] ?? null,
                        'notes' => $test['advisoryNotes'] ?? null,
                        'defects' => $test['defects'] ?? [],
                    ]
                );
            }
        }
    }

    public function getVehicleDetails(string $registrationNumber): ?array
    {
        $registration = $this->normalizeRegistration($registrationNumber);
        
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'x-api-key' => $this->apiKey,
            ])->get("{$this->baseUrl}/vehicle/{$registration}");

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }
}