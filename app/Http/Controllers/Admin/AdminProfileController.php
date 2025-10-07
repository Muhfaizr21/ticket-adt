<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminProfileController extends Controller
{
    // Tampilkan profile admin
    public function index()
    {
        $admin = auth()->user();
        return view('admin.profile.index', compact('admin'));
    }

    // Update profile (nama, email, position, phone, avatar)
    public function update(Request $request)
    {
        $admin = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $admin->id,
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $admin->name = $validated['name'];
        $admin->email = $validated['email'];
        $admin->phone = $validated['phone'] ?? null;
        $admin->position = $validated['position'] ?? null;

        if ($request->hasFile('avatar')) {
            if ($admin->avatar && Storage::disk('public')->exists($admin->avatar)) {
                Storage::disk('public')->delete($admin->avatar);
            }
            $admin->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $admin->save();

        return redirect()->route('admin.profile.index')
            ->with('success', 'Profile berhasil diperbarui!');
    }

    // Update password
    public function updatePassword(Request $request)
    {
        $admin = auth()->user();

        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($validated['current_password'], $admin->password)) {
            return back()->withErrors(['current_password' => 'Password lama salah']);
        }

        $admin->password = Hash::make($validated['password']);
        $admin->save();

        return redirect()->route('admin.profile.index')
            ->with('success', 'Password berhasil diperbarui!');
    }

    // Update avatar terpisah via form khusus (opsional)
    public function updateAvatar(Request $request)
    {
        $admin = auth()->user();

        $validated = $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($admin->avatar && Storage::disk('public')->exists($admin->avatar)) {
            Storage::disk('public')->delete($admin->avatar);
        }

        $admin->avatar = $request->file('avatar')->store('avatars', 'public');
        $admin->save();

        return redirect()->route('admin.profile.index')
            ->with('success', 'Avatar berhasil diperbarui!');
    }
}
