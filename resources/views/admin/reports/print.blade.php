<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi - Grooming Lab & MUA</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #1a202c;
            margin: 0;
            padding: 24px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0 0 4px;
            font-size: 20px;
            text-transform: uppercase;
        }
        .header p {
            margin: 2px 0;
            color: #666;
            font-size: 11px;
        }
        .meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 16px;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #cbd5e0;
            padding: 8px 10px;
            text-align: left;
        }
        th {
            background-color: #f7fafc;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
        }
        .text-right {
            text-align: right;
        }
        .total-row {
            font-weight: bold;
            background-color: #f7fafc;
        }
        .print-btn {
            background: #2563eb;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            margin-bottom: 16px;
            font-size: 12px;
            font-weight: bold;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" class="print-btn">&#128438; Cetak Dokumen / Simpan Sebagai PDF</button>
    </div>

    <div class="header">
        <h1>GROOMING LAB & MUA STUDIO</h1>
        <p>Laporan Rekapitulasi Transaksi Layanan & Pendapatan</p>
        <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }} | Kategori: {{ ucfirst($category) }}</p>
    </div>

    <div class="meta">
        <div>
            <strong>Dicetak Oleh:</strong> {{ auth()->user()->name }} (Admin)<br>
            <strong>Waktu Cetak:</strong> {{ now()->translatedFormat('d F Y H:i') }} WIB
        </div>
        <div style="text-align: right;">
            <strong>Total Order Selesai:</strong> {{ $bookings->count() }} Transaksi<br>
            <strong>Total Pendapatan:</strong> Rp {{ number_format($totalRevenue, 0, ',', '.') }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th>Tgl & Waktu</th>
                <th>No. Antrian</th>
                <th>Kode Booking</th>
                <th>Pelanggan</th>
                <th>Kategori</th>
                <th>Layanan</th>
                <th>Kasir</th>
                <th class="text-right">Total (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($bookings as $index => $b)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $b->booking_date->format('d/m/Y') }} {{ $b->booking_time }}</td>
                    <td style="font-weight: bold;">{{ $b->queue_number }}</td>
                    <td style="font-family: monospace;">{{ $b->booking_code }}</td>
                    <td>{{ $b->user->name ?? 'Walk-in' }}</td>
                    <td>{{ ucfirst($b->service_category) }}</td>
                    <td>{{ $b->items->map(fn($i) => $i->service->name)->join(', ') }}</td>
                    <td>{{ $b->cashier->name ?? '-' }}</td>
                    <td class="text-right">{{ number_format($b->total_price, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center; color: #718096; padding: 16px;">
                        Tidak ada data transaksi selesai pada periode ini.
                    </td>
                </tr>
            @endforelse
            @if ($bookings->count() > 0)
                <tr class="total-row">
                    <td colspan="8" class="text-right">TOTAL PENDAPATAN :</td>
                    <td class="text-right">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div style="margin-top: 40px; display: flex; justify-content: flex-end;">
        <div style="text-align: center; width: 200px;">
            <p style="margin-bottom: 60px;">Penanggung Jawab,</p>
            <p style="font-weight: bold; border-top: 1px solid #718096; padding-top: 4px;">{{ auth()->user()->name }}</p>
        </div>
    </div>

    <script>
        // Auto trigger print if opened in standalone window
        window.addEventListener('load', function() {
            // Optional: window.print();
        });
    </script>
</body>
</html>
