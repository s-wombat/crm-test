<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;

class OpenWeatherService
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.openweather.base_url');
        $this->apiKey = config('services.openweather.api_key');
    }

    public function getCurrentWeather($city)
    {
        $response = Http::get("{$this->baseUrl}", [
            'q' => $city,
            'appid' => $this->apiKey,
            'units' => 'metric',
            'lang' => 'ru',
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new Exception('Unable to fetch weather data.');

        return null;
    }
}
