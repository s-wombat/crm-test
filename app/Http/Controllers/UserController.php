<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;

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
        $users = $query->orderBy('id', 'desc')->paginate(10);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.edit');
    }

    public function store(StoreUserRequest $request)
    {

        $validated = $request->validated();
        User::create($validated);

        return redirect()->route('users.index')->with('success', 'Клиент успешно добавлен.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
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
