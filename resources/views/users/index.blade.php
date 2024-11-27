@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Список клиентов</h1>

    <form method="GET" class="mb-4">
        <input type="text" name="name" placeholder="Имя" value="{{ request('name') }}">
        <select name="status">
            <option value="">Все статусы</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Активный</option>
            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Неактивный</option>
        </select>
        <button type="submit">Фильтровать</button>
    </form>

    <a href="{{ route('users.create') }}" class="btn btn-primary">Добавить клиента</a>

    <table class="table mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Email</th>
                <th>Телефон</th>
                <th>Дата регистрации</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>{{ $user->registered_at }}</td>
                    <td>{{ $user->status }}</td>
                    <td>
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">Редактировать</a>
                        <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Удалить</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $users->links() }}
</div>
@endsection
