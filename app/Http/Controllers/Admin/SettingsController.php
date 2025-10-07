<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class SettingsController extends Controller
{
    // Halaman index settings (overview)
    public function index()
    {
        $admin = auth()->user(); // Ambil admin yang login
        return view('admin.settings.index', compact('admin'));
    }

    // Tampilkan form edit settings
    public function edit()
    {
        $admin = auth()->user(); // Ambil admin yang login
        return view('admin.settings.edit', compact('admin'));
    }

    // Update data umum (nama, email, password, phone, position, language, avatar)
    public function update(Request $request)
    {
        $admin = auth()->user();

        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($admin->id)],
            'password' => 'nullable|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
            'language' => 'required|in:id,en',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $admin->name = $validated['name'];
        $admin->email = $validated['email'];
        $admin->phone = $validated['phone'] ?? null;
        $admin->position = $validated['position'] ?? null;
        $admin->language = $validated['language'];

        if (!empty($validated['password'])) {
            $admin->password = Hash::make($validated['password']);
        }

        if ($request->hasFile('avatar')) {
            if ($admin->avatar && Storage::disk('public')->exists($admin->avatar)) {
                Storage::disk('public')->delete($admin->avatar);
            }
            $admin->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $admin->save();

        return redirect()->route('admin.settings.edit')
            ->with('success', 'Pengaturan admin berhasil diperbarui!');
    }

    // Update theme via AJAX
    public function updateTheme(Request $request)
    {
        $request->validate([
            'theme' => 'required|in:light,dark',
        ]);

        $admin = auth()->user();
        $admin->theme = $request->theme;
        $admin->save();

        return response()->json([
            'success' => true,
            'theme' => $admin->theme,
            'message' => 'Tema berhasil diperbarui!'
        ]);
    }
}
