<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan Tiket</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background: #f2f2f2; }
        h3 { text-align: center; }
    </style>
</head>
<body>
    <h3>Laporan Penjualan Tiket</h3>
    <p><strong>Total Pendapatan:</strong> Rp{{ number_format($totalRevenue, 0, ',', '.') }}</p>
    <p><strong>Total Tiket Terjual:</strong> {{ $totalTickets }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Event</th>
                <th>Venue</th>
                <th>User</th>
                <th>Tiket</th>
                <th>Total Harga</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $order->event->name ?? '-' }}</td>
                    <td>{{ $order->event->venue->name ?? '-' }}</td>
                    <td>{{ $order->user->name ?? '-' }}</td>
                    <td>{{ $order->quantity }}</td>
                    <td>Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($order->status) }}</td>
                    <td>{{ $order->created_at->format('d M Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
