<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $pesanan->id }} - {{ $toko->nama_toko }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #1B4332;
            padding-bottom: 15px;
        }
        .header h2 {
            color: #1B4332;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        .header p {
            margin: 2px 0;
            font-size: 0.9rem;
            color: #555;
        }
        .details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .details div {
            font-size: 0.9rem;
        }
        .details p {
            margin: 3px 0;
        }
        .details strong {
            color: #1B4332;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            color: #1B4332;
            font-weight: 600;
        }
        td.right, th.right {
            text-align: right;
        }
        td.center, th.center {
            text-align: center;
        }
        .total-section {
            text-align: right;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px solid #1B4332;
        }
        .total-section p {
            margin: 5px 0;
            font-size: 1rem;
        }
        .total-section .grand-total {
            font-size: 1.5rem;
            font-weight: bold;
            color: #1B4332;
        }
        .footer-note {
            margin-top: 50px;
            text-align: center;
            font-size: 0.8rem;
            color: #888;
            border-top: 1px dashed #ccc;
            padding-top: 15px;
        }
        @media print {
            body {
                padding: 0;
            }
            .invoice-box {
                border: none;
                box-shadow: none;
                padding: 0;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="invoice-box">
        <div class="header">
            <h2>{{ $toko->nama_toko }}</h2>
            <p>{{ $toko->alamat }}</p>
            <p>No. Telp: {{ $toko->user->no_telp }}</p>
            <h3 style="margin-top: 15px; color: #555;">INVOICE PESANAN #{{ $pesanan->id }}</h3>
        </div>

        <div class="details">
            <div>
                <p><strong>Dikirim Kepada:</strong></p>
                <p>{{ $pesanan->user->name }}</p>
                <p>{{ $pesanan->user->alamat }}</p>
                <p>{{ $pesanan->user->no_telp }}</p>
            </div>
            <div style="text-align: right;">
                <p><strong>Tanggal:</strong> {{ $pesanan->created_at->format('d M Y') }}</p>
                <p><strong>Ekspedisi:</strong> {{ $pesanan->ekspedisi }}</p>
                <p><strong>Metode Bayar:</strong> {{ ucfirst($pesanan->metode_pembayaran) }}</p>
                <p><strong>Status:</strong> {{ ucfirst($pesanan->status) }}</p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th class="center">Jumlah</th>
                    <th class="right">Harga Satuan</th>
                    <th class="right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pesanan->detailPesanans as $detail)
                <tr>
                    <td>{{ $detail->produk->nama_produk }}</td>
                    <td class="center">{{ $detail->jumlah }}</td>
                    <td class="right">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                    <td class="right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            <p>Subtotal Produk: Rp {{ number_format($pesanan->total_harga - $pesanan->ongkir, 0, ',', '.') }}</p>
            <p>Ongkir ({{ $pesanan->ekspedisi }}): Rp {{ number_format($pesanan->ongkir, 0, ',', '.') }}</p>
            <p class="grand-total">TOTAL: Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</p>
        </div>

        <div class="footer-note">
            *Ini adalah invoice otomatis dari sistem Marketplace UMKM Nagari Lawang. <br>
            Terima kasih telah berbelanja dan mendukung UMKM lokal!
        </div>
    </div>

</body>
</html>