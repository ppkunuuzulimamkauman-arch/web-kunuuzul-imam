<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $q = User::query();
        // Pengelola tidak melihat akun utama.
        if (! auth()->user()->isUtama()) {
            $q->whereNotIn('role', ['superadmin', 'admin']);
        }
        return view('admin.users.index', ['users' => $q->latest()->paginate(12)]);
    }

    public function create(): View
    {
        return view('admin.users.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'nullable|in:superadmin,operator',
        ]);

        // Pengelola hanya boleh membuat sesama pengelola.
        $role = auth()->user()->isUtama() ? ($data['role'] ?? 'operator') : 'operator';

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $role,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.users.index')->with('sukses', 'Akun baru dibuat.');
    }

    public function edit(User $user): View
    {
        $this->authorizeTarget($user);
        return view('admin.users.form', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->authorizeTarget($user);
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'nullable|in:superadmin,operator',
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        if (auth()->user()->isUtama()) {
            $newRole = $data['role'] ?? $user->role;
            // Jangan biarkan admin utama terakhir diturunkan.
            if ($user->isUtama() && $newRole !== $user->role
                && User::whereIn('role', ['superadmin', 'admin'])->count() <= 1) {
                return back()->withErrors(['email' => 'Tidak boleh menurunkan satu-satunya admin utama.']);
            }
            $user->role = $newRole;
        }
        if (! $user->is(auth()->user())) {
            $user->is_active = $request->boolean('is_active');
        }
        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        return redirect()->route('admin.users.index')->with('sukses', 'Akun diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorizeTarget($user);
        if ($user->is(auth()->user())) {
            return back()->withErrors(['email' => 'Tidak boleh menghapus akun sendiri.']);
        }
        if (User::count() <= 1) {
            return back()->withErrors(['email' => 'Tidak boleh menghapus satu-satunya admin.']);
        }
        if ($user->isUtama() && User::whereIn('role', ['superadmin', 'admin'])->count() <= 1) {
            return back()->withErrors(['email' => 'Tidak boleh menghapus satu-satunya admin utama.']);
        }
        $user->delete();
        return back()->with('sukses', 'Akun dihapus.');
    }

    public function toggleActive(User $user): RedirectResponse
    {
        $this->authorizeTarget($user);
        if ($user->is(auth()->user())) {
            return back()->withErrors(['email' => 'Tidak boleh menonaktifkan akun sendiri.']);
        }
        $user->update(['is_active' => ! $user->is_active]);
        return back()->with('sukses', 'Status akun diubah.');
    }

    /** Pengelola tidak boleh menyentuh akun utama (bahkan via URL langsung). */
    protected function authorizeTarget(User $user): void
    {
        if ($user->isUtama() && ! auth()->user()->isUtama()) {
            abort(403, 'Anda tidak memiliki akses ke akun utama.');
        }
    }
}
