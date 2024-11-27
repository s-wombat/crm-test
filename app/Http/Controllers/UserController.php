<?php

namespace App\Http\Controllers;

use App\Enums\StatusTypes;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Фильтрация
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Пагинация
        $users = $query->orderBy('id', 'asc')->paginate(10);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.edit');
    }

    public function show(User $user)
    {
        $user = User::find($user->id);
        $weather = $this->getWeather($user->city);

        return view('users.show', compact('user', 'weather'));
    }

    public function store(StoreUserRequest $request)
    {

        $validated = $request->validated();
        User::create($validated);

        return redirect()->route('users.index')->with('success', 'Клиент успешно добавлен.');
    }

    public function edit(User $user)
    {
        $weather = $this->getWeather($user->city);

        return view('users.edit', compact('user', 'weather'));
    }

    private function getWeather($city)
    {
        if (!$city) {
            return null;
        }

        // Кэшируем данные на 30 минут
        return Cache::remember("weather_{$city}", now()->addMinutes(30), function () use ($city) {
            $response = Http::get(config('services.openweather.base_url'), [
                'q' => $city,
                'appid' => config('services.openweather.api_key'),
                'units' => 'metric',
                'lang' => 'ru',
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        });
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();
        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'Данные клиента обновлены.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Клиент удален.');
    }
}
