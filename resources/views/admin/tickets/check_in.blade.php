@extends('admin.layouts.app')

@section('content')
    <div class="container py-5">
        <h1 class="mb-4">🎫 Ticket Check-In Real-Time</h1>

        <div class="row">
            {{-- Scanner --}}
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">📷 Scan QR Code</h5>
                    </div>
                    <div class="card-body">
                        <div id="alert-container"></div>
                        <div id="qr-reader" style="width:100%; max-width:400px;"></div>
                    </div>
                </div>
            </div>

            {{-- Daftar tiket aktif --}}
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">📋 Daftar Tiket Aktif</h5>
                    </div>
                    <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nama User</th>
                                    <th>Barcode</th>
                                    <th>Status Check-In</th>
                                </tr>
                            </thead>
                            <tbody id="orders-table">
                                @foreach($orders as $order)
                                    <tr id="order-{{ $order->id }}">
                                        <td>{{ $order->user->name }}</td>
                                        <td>{{ $order->barcode_code }}</td>
                                        <td>
                                            @if($order->checked_in_at)
                                                <span class="badge bg-success">✔ Checked-In</span>
                                            @else
                                                <span class="badge bg-warning">Pending</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.5.1/axios.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const alertContainer = document.getElementById('alert-container');

            function showAlert(message, type = 'success') {
                const alertDiv = document.createElement('div');
                alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
                alertDiv.innerHTML = `${type === 'success' ? '✅' : '❌'} ${message} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
                alertContainer.innerHTML = '';
                alertContainer.appendChild(alertDiv);
            }

            function onScanSuccess(decodedText, decodedResult) {
                html5QrcodeScanner.clear();

                axios.post('{{ route("admin.tickets.verify") }}', {
                    barcode_code: decodedText,
                    _token: '{{ csrf_token() }}'
                })
                    .then(res => {
                        const data = res.data;
                        if (data.status === 'success') {
                            showAlert(data.message, 'success');
                            // Update tabel realtime
                            const row = document.getElementById('order-' + data.order_id);
                            if (row) {
                                row.querySelector('td:nth-child(3)').innerHTML = '<span class="badge bg-success">✔ Checked-In</span>';
                            }
                        } else {
                            showAlert(data.message, 'danger');
                        }
                    })
                    .catch(err => {
                        showAlert('Terjadi kesalahan server', 'danger');
                    });

                // restart scanner setelah 2 detik
                setTimeout(() => {
                    html5QrcodeScanner.render(onScanSuccess);
                }, 2000);
            }

            let html5QrcodeScanner = new Html5QrcodeScanner("qr-reader", { fps: 10, qrbox: 250 }, false);
            html5QrcodeScanner.render(onScanSuccess);

        });
    </script>
@endsection