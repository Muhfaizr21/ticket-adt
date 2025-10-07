<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // untuk export PDF
use Carbon\Carbon;

class ReportController extends Controller
{
    // 🟢 Tampilkan halaman laporan
    public function index(Request $request)
    {
        $query = Order::with(['event.venue', 'user']);

        // 🔍 Filter tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // 🔍 Filter status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(10);

        // Statistik
        $totalRevenue = $query->sum('total_price');
        $totalTickets = $query->sum('quantity');

        return view('admin.reports.index', compact('orders', 'totalRevenue', 'totalTickets'));
    }

    // 🟢 Export ke PDF
    public function exportPdf(Request $request)
    {
        $query = Order::with(['event.venue', 'user']);

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $orders = $query->get();
        $totalRevenue = $orders->sum('total_price');
        $totalTickets = $orders->sum('quantity');

        $pdf = Pdf::loadView('admin.reports.pdf', compact('orders', 'totalRevenue', 'totalTickets'))
            ->setPaper('a4', 'portrait');

        $filename = 'laporan_tiket_' . Carbon::now()->format('Ymd_His') . '.pdf';
        return $pdf->download($filename);
    }
}
