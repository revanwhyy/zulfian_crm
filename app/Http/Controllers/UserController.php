<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::query()->where('role', 'sales')->latest()->get();
        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        return view('users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => 'sales',
        ]);

        return redirect()->route('users.index')->with('success', 'Sales berhasil dibuat.');
    }

    public function edit(User $user): View
    {
        if ($user->role !== 'sales') {
            abort(404);
        }
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        if ($user->role !== 'sales') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];
        if (!empty($validated['password'])) {
            $data['password'] = $validated['password'];
        }
        $data['role'] = 'sales';

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Sales berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->role !== 'sales') {
            abort(404);
        }
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Sales berhasil dihapus.');
    }
}
