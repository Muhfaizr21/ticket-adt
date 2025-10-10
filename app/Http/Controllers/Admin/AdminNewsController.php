<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AdminNewsController extends Controller
{
    /**
     * Tampilkan semua berita
     */
    public function index()
    {
        $news = News::latest()->paginate(10);
        return view('admin.news.index', compact('news'));
    }

    /**
     * Halaman tambah berita
     */
    public function create()
    {
        return view('admin.news.create');
    }

    /**
     * Simpan berita baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'content'       => 'required|string',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'published_at'  => 'nullable|date',
        ]);

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('news', 'public');
        }

        News::create([
            'title'         => $request->title,
            'slug'          => Str::slug($request->title) . '-' . uniqid(), // ✅ slug unik
            'content'       => $request->content,
            'image'         => $path,
            'author'        => auth()->user()->name ?? 'Admin',
            'published_at'  => $request->published_at ?? Carbon::now(),
        ]);

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil ditambahkan.');
    }

    /**
     * Halaman edit berita
     */
    public function edit($id)
    {
        $news = News::findOrFail($id);
        return view('admin.news.edit', compact('news'));
    }

    /**
     * Update berita
     */
    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);

        $request->validate([
            'title'         => 'required|string|max:255',
            'content'       => 'required|string',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'published_at'  => 'nullable|date',
        ]);

        $path = $news->image;
        if ($request->hasFile('image')) {
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            $path = $request->file('image')->store('news', 'public');
        }

        $news->update([
            'title'         => $request->title,
            'slug'          => Str::slug($request->title) . '-' . uniqid(), // ✅ slug update juga
            'content'       => $request->content,
            'image'         => $path,
            'published_at'  => $request->published_at ?? $news->published_at ?? now(),
        ]);

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil diperbarui.');
    }

    /**
     * Hapus berita
     */
    public function destroy($id)
    {
        $news = News::findOrFail($id);

        if ($news->image && Storage::disk('public')->exists($news->image)) {
            Storage::disk('public')->delete($news->image);
        }

        $news->delete();

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil dihapus.');
    }
}
