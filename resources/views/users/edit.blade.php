@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ isset($user) ? 'Редактировать клиента' : 'Добавить клиента' }}</h1>
    @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

    <form method="POST" action="{{ isset($user) ? route('users.update', $user) : route('users.store') }}">
        @csrf
        @if(isset($user))
            @method('PUT')
        @endif

        <div class="mb-3">
            <label for="name">Имя</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="phone">Телефон</label>
            <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $user->phone ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="registered_at">Дата регистрации</label>
            <input type="date" name="registered_at" id="registered_at" class="form-control" value="{{ old('registered_at', $user->registered_at ?? now()->format('Y-m-d')) }}" required>
        </div>

        <div class="mb-3">
            <label for="status">Статус</label>
            <select name="status" id="status" class="form-control">
                <option value="active" {{ old('status', $user->status ?? '') == 'active' ? 'selected' : '' }}>Активный</option>
                <option value="inactive" {{ old('status', $user->status ?? '') == 'inactive' ? 'selected' : '' }}>Неактивный</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="city">Город</label>
            <input type="text" name="city" id="city" class="form-control" value="{{ old('city', $user->city ?? '') }}">
        </div>
        
        @if(isset($weather))
            <div class="mb-3">
                <h4>Текущая погода в {{ $user->city }}</h4>
                <p>Температура: {{ $weather['main']['temp'] }} °C</p>
                <p>Описание: {{ $weather['weather'][0]['description'] }}</p>
                <p>Влажность: {{ $weather['main']['humidity'] }}%</p>
                <p>Скорость ветра: {{ $weather['wind']['speed'] }} м/с</p>
            </div>
        @endif

        <button type="submit" class="btn btn-success">Сохранить</button>
    </form>
</div>
@endsection