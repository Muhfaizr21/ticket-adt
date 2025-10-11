@extends('admin.layouts.app')

@section('content')
    <div class="container py-5">
        <h1 class="mb-4">🎫 Ticket Check-In Real-Time</h1>

        <div class="row">
            {{-- Scanner --}}
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">📷 Scan QR Code</h5>
                        <button id="btn-clear-log" class="btn btn-sm btn-light">🧹 Clear Log</button>
                    </div>
                    <div class="card-body">
                        <div id="alert-container"></div>
                        <div id="qr-reader" style="width:100%; max-width:400px;"></div>

                        <hr>
                        <h6>📜 Log Scan Terbaru</h6>
                        <table class="table table-sm table-hover mt-3">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>Barcode</th>
                                    <th>Hasil</th>
                                </tr>
                            </thead>
                            <tbody id="scan-log">
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Belum ada aktivitas scan</td>
                                </tr>
                            </tbody>
                        </table>
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

    {{-- Scripts --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.5.1/axios.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const alertContainer = document.getElementById('alert-container');
            const scanLog = document.getElementById('scan-log');
            const btnClearLog = document.getElementById('btn-clear-log');

            function showAlert(message, type = 'success', timeout = 3000) {
                const alertDiv = document.createElement('div');
                alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
                alertDiv.innerHTML = `${type === 'success' ? '✅' : '❌'} ${message} 
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
                alertContainer.innerHTML = '';
                alertContainer.appendChild(alertDiv);
                if (timeout > 0) setTimeout(() => alertDiv.remove(), timeout);
            }

            function addScanLog(barcode, message, success = true) {
                const now = new Date().toLocaleTimeString();
                const row = document.createElement('tr');
                row.innerHTML = `
                <td>${now}</td>
                <td>${barcode}</td>
                <td>${success ? '<span class="badge bg-success">✔ ' + message + '</span>'
                        : '<span class="badge bg-danger">❌ ' + message + '</span>'}</td>
            `;
                if (scanLog.children.length === 1 && scanLog.children[0].textContent.includes("Belum")) {
                    scanLog.innerHTML = '';
                }
                scanLog.prepend(row);
            }

            btnClearLog.addEventListener('click', () => {
                scanLog.innerHTML = '<tr><td colspan="3" class="text-center text-muted">Belum ada aktivitas scan</td></tr>';
            });

            function onScanSuccess(decodedText) {
                // Bersihkan barcode: hapus spasi, karakter non-hexadecimal, ubah lowercase
                let cleanBarcode = decodedText.trim().replace(/[^a-f0-9-]/gi, '').toLowerCase();

                html5QrcodeScanner.clear().then(() => {
                    axios.post('{{ route("admin.tickets.verify") }}', {
                        barcode_code: cleanBarcode,
                        _token: '{{ csrf_token() }}'
                    })
                        .then(res => {
                            const data = res.data;
                            if (data.status === 'success') {
                                showAlert(data.message, 'success');
                                addScanLog(cleanBarcode, data.message, true);

                                const row = document.getElementById('order-' + data.order_id);
                                if (row) row.querySelector('td:nth-child(3)').innerHTML =
                                    '<span class="badge bg-success">✔ Checked-In</span>';
                            } else {
                                showAlert(data.message, 'danger');
                                addScanLog(cleanBarcode, data.message, false);
                            }
                        })
                        .catch(() => {
                            showAlert('Terjadi kesalahan server', 'danger');
                            addScanLog(cleanBarcode, 'Server Error', false);
                        })
                        .finally(() => {
                            setTimeout(() => html5QrcodeScanner.render(onScanSuccess), 1000);
                        });
                }).catch(err => console.error('Error clearing QR scanner', err));
            }

            const html5QrcodeScanner = new Html5QrcodeScanner("qr-reader", { fps: 10, qrbox: 250 }, false);
            html5QrcodeScanner.render(onScanSuccess);
        });
    </script>
@endsection