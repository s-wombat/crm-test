@extends('layouts.app')

@section('content')
<div class="mb-3">
    <h6 for="name">Имя: {{ $user->name }}</h6>
    <h6 for="name">Email: {{ $user->email }}</h6>
    <h6 for="name">Телефон: {{ $user->phone }}</h6>
    <h6 for="name">Дата регистрации: {{ $user->registered_at }}</h6>
    <h6 for="name">Статус: {{ $user->status }}</h6>
</div>

@if(isset($weather))
    <div class="mb-3">
        <h4>Погода в {{ $user->city }}</h4>
        <p>Температура: {{ $weather['main']['temp'] }} °C</p>
        <p>Описание: {{ $weather['weather'][0]['description'] }}</p>
        <p>Влажность: {{ $weather['main']['humidity'] }}%</p>
        <p>Скорость ветра: {{ $weather['wind']['speed'] }} м/с</p>
    </div>
@endif
@endsection