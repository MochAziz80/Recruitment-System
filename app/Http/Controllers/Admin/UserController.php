<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $sort = $request->input('sort', 'name');
        $direction = $request->input('direction', 'asc');
        $role = $request->input('role', null);

        $users = User::query()
            ->when($search, fn($query) => 
                $query->where(fn($q) => 
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%")
                )
            )
            ->when($role, fn($query) => $query->where('role', $role))
            ->orderBy($sort, $direction)
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'filters' => compact('search', 'sort', 'direction', 'role')
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Users/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:administrator,applicant',
            'phone' => 'nullable|string|max:20',
            'cv' => 'nullable|file|mimes:pdf|max:512',
            'application_status' => 'nullable|in:pending,approved,rejected',
        ]);

        $cvPath = null;
        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('cv', 'public');
        }

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
            'cv_path' => $cvPath,
            'application_status' => $validated['application_status'] ?? null,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dibuat.');
    }

    public function edit(User $user)
    {
        return Inertia::render('Admin/Users/Edit', [
            'user' => $user,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required','string','email','max:255', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:administrator,applicant',
            'phone' => 'nullable|string|max:20',
            'cv' => 'nullable|file|mimes:pdf|max:512',
            'application_status' => 'nullable|in:pending,approved,rejected',
        ]);

        if ($request->hasFile('cv')) {
            if ($user->cv_path) {
                Storage::disk('public')->delete($user->cv_path);
            }
            $user->cv_path = $request->file('cv')->store('cv', 'public');
        }

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            // 'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
            'application_status' => $validated['application_status'] ?? null,
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8']);
            $user->password = Hash::make($request->password);
        }

        $user->save();
        $user->syncRoles([$validated['role']]);
        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->cv_path) {
            Storage::disk('public')->delete($user->cv_path);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}
