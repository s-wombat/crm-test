<?php

namespace Tests\Unit;

use Exception;
use App\Services\OpenWeatherService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_fetches_current_weather_successfully()
    {
        // Подделываем успешный ответ от OpenWeather API
        Http::fake([
            'api.openweathermap.org/*' => Http::response([
                'weather' => [
                    ['description' => 'clear sky']
                ],
                'main' => [
                    'temp' => 20.5,
                    'humidity' => 55,
                ],
                'name' => 'London',
            ], 200),
        ]);

        $service = new OpenWeatherService();

        $weather = $service->getCurrentWeather('London');

        // Проверяем, что данные корректно обработаны
        $this->assertEquals('London', $weather['name']);
        $this->assertEquals(20.5, $weather['main']['temp']);
        $this->assertEquals('clear sky', $weather['weather'][0]['description']);
    }

    public function test_it_handles_api_errors_gracefully()
    {
        // Подделываем ошибочный ответ от OpenWeather API
        Http::fake([
            'api.openweathermap.org/*' => Http::response([], 404),
        ]);

        $service = new OpenWeatherService();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Unable to fetch weather data.');

        $service->getCurrentWeather('InvalidCity');
    }

}

