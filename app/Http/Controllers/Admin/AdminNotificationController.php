<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    // 🟢 Tampilkan semua notifikasi
    public function index()
    {
        $notifications = Notification::latest()->paginate(10);
        return view('admin.pages.notifications.index', compact('notifications'));
    }

    // 🟢 Tandai notifikasi sebagai dibaca
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['is_read' => true]);

        return redirect()->back()->with('success', '✅ Notifikasi ditandai sebagai dibaca.');
    }

    // 🟢 Tandai semua sebagai dibaca
    public function markAllAsRead()
    {
        Notification::where('is_read', false)->update(['is_read' => true]);
        return redirect()->back()->with('success', '✅ Semua notifikasi ditandai sebagai dibaca.');
    }
}
