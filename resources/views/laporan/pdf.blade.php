@php
    $groupedLaporan = [];
    $totalKeuntungan = 0;

    if (isset($laporans) && count($laporans) > 0) {
        foreach ($laporans as $laporan) {
            $namaProduk = $laporan->produk->name ?? 'Tidak Diketahui';
            if (!isset($groupedLaporan[$namaProduk])) {
                $groupedLaporan[$namaProduk] = [
                    'id' => $laporan->id,
                    'nama_produk' => $namaProduk,
                    'jumlah_terjual' => 0,
                    'harga_jual' => $laporan->produk->harga_jual ?? 0,
                    'harga_beli' => $laporan->produk->harga_beli ?? 0,
                    'keuntungan' => 0
                ];
            }
            $groupedLaporan[$namaProduk]['jumlah_terjual'] += $laporan->jumlah_terjual ?? 0;
            $groupedLaporan[$namaProduk]['keuntungan'] += $laporan->keuntungan ?? 0;

            // Tambahkan ke total keuntungan
            $totalKeuntungan += $laporan->keuntungan ?? 0;
        }
    }
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid black; padding: 5px; text-align: center; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Laporan Penjualan</h2>

    @if(count($groupedLaporan) > 0)
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Jumlah Terjual</th>
                    <th>Harga Jual</th>
                    <th>Harga Beli</th>
                    <th>Keuntungan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($groupedLaporan as $index => $laporan)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $laporan['nama_produk'] }}</td>
                        <td>{{ $laporan['jumlah_terjual'] }}</td>
                        <td>Rp{{ number_format($laporan['harga_jual'], 0, ',', '.') }}</td>
                        <td>Rp{{ number_format($laporan['harga_beli'], 0, ',', '.') }}</td>
                        <td>Rp{{ number_format($laporan['keuntungan'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                <!-- Baris Total Keuntungan -->
                <tr>
                    <td colspan="5"><strong>Total Keuntungan</strong></td>
                    <td><strong>Rp{{ number_format($totalKeuntungan, 0, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>
    @else
        <p style="text-align: center; font-weight: bold;">Tidak ada data laporan yang tersedia.</p>
    @endif
</body>
</html>
