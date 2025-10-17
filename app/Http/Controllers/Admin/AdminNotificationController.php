<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    /**
     * 🟢 Menampilkan semua notifikasi
     */
    public function index()
    {
        $notifications = Notification::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.pages.notifications.index', compact('notifications'));
    }

    /**
     * 🟡 Tandai satu notifikasi sebagai dibaca
     */
    public function markAsRead($id)
    {
        $notification = Notification::find($id);

        if (!$notification) {
            return redirect()->back()->with('error', '❌ Notifikasi tidak ditemukan.');
        }

        if (!$notification->is_read) {
            $notification->update(['is_read' => true]);
        }

        return redirect()->back()->with('success', '✅ Notifikasi berhasil ditandai sebagai dibaca.');
    }

    /**
     * 🟠 Tandai semua notifikasi sebagai dibaca
     */
    public function markAllAsRead()
    {
        Notification::where('is_read', false)->update(['is_read' => true]);

        return redirect()->back()->with('success', '✅ Semua notifikasi berhasil ditandai sebagai dibaca.');
    }
}
