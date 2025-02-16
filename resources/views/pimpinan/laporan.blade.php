{{-- <!-- filepath: /c:/laragon/www/tokolaptop/resources/views/pimpinan/laporan.blade.php -->
@extends('layouts.mainPimpinan')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Laporan Penjualan</h4>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <input type="date" id="filterTanggal" class="form-control w-25" placeholder="Filter Tanggal">
                    </div>
                    <table id="laporanTable" class="table align-items-center mb-0 table-striped table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>No</th>
                                <th>No. Transaksi</th>
                                <th>Tanggal</th>
                                <th>Total Penjualan</th>
                            </tr>
                        </thead>
                        <tbody id="laporanTableBody">
                            @foreach ($laporans as $laporan)
                                <tr data-bs-toggle="modal" data-bs-target="#detailPenjualanModal" data-laporan="{{ json_encode($laporan) }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $laporan->penjualan->no_transaksi }}</td>
                                    <td>{{ $laporan->penjualan->created_at->format('d-m-Y') }}</td>
                                    <td>Rp. {{ number_format($laporan->produk->harga_jual, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Penjualan -->
<div class="modal fade" id="detailPenjualanModal" tabindex="-1" aria-labelledby="detailPenjualanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailPenjualanModalLabel">Detail Penjualan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row mb-2">
                        <div class="col-4"><strong>No. Transaksi</strong></div>
                        <div class="col-1 text-center"><strong>:</strong></div>
                        <div class="col-7" id="detailNoTransaksi"></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4"><strong>Nama Pelanggan</strong></div>
                        <div class="col-1 text-center"><strong>:</strong></div>
                        <div class="col-7" id="detailNamaPelanggan"></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4"><strong>Tanggal</strong></div>
                        <div class="col-1 text-center"><strong>:</strong></div>
                        <div class="col-7" id="detailTanggal"></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4"><strong>Barang</strong></div>
                        <div class="col-1 text-center"><strong>:</strong></div>
                        <div class="col-7">
                            <ul id="detailProdukList" class="list-unstyled mb-0"></ul>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4"><strong>Total</strong></div>
                        <div class="col-1 text-center"><strong>:</strong></div>
                        <div class="col-7" id="detailTotal"></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4"><strong>Uang Pelanggan</strong></div>
                        <div class="col-1 text-center"><strong>:</strong></div>
                        <div class="col-7" id="detailUangAnda"></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4"><strong>Kembalian</strong></div>
                        <div class="col-1 text-center"><strong>:</strong></div>
                        <div class="col-7" id="detailKembalian"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="js/scripts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="assets/demo/chart-area-demo.js"></script>
<script src="assets/demo/chart-bar-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
<script src="js/datatables-simple-demo.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterTanggal = document.getElementById('filterTanggal');
        const laporanTableBody = document.getElementById('laporanTableBody');
        const tableRows = Array.from(laporanTableBody.rows);

        filterTanggal.addEventListener('change', function() {
            filterTable();
        });

        function formatDate(dateString) {
            const dateObj = new Date(dateString);
            if (isNaN(dateObj)) return null; // Menghindari error jika tanggal tidak valid
            const day = String(dateObj.getDate()).padStart(2, '0');
            const month = String(dateObj.getMonth() + 1).padStart(2, '0');
            const year = dateObj.getFullYear();
            return `${day}-${month}-${year}`;
        }

        function filterTable() {
            const selectedDate = filterTanggal.value;
            const formattedDate = formatDate(selectedDate);

            tableRows.forEach(row => {
                const rowDate = row.cells[2].innerText.trim(); // Ambil teks tanggal dari tabel
                if (!selectedDate || rowDate === formattedDate) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }

        // Event listener untuk menampilkan detail penjualan di modal
        const detailPenjualanModal = document.getElementById('detailPenjualanModal');
        detailPenjualanModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const laporan = JSON.parse(button.getAttribute('data-laporan'));

            document.getElementById('detailNoTransaksi').innerText = laporan.penjualan.no_transaksi;
            document.getElementById('detailNamaPelanggan').innerText = laporan.penjualan.user.name;
            document.getElementById('detailTanggal').innerText = new Date(laporan.penjualan.created_at).toLocaleDateString('id-ID');
            document.getElementById('detailTotal').innerText = 'Rp. ' + new Intl.NumberFormat('id-ID').format(laporan.penjualan.total_harga);
            document.getElementById('detailUangAnda').innerText = 'Rp. ' + new Intl.NumberFormat('id-ID').format(laporan.penjualan.uang_pelanggan);
            document.getElementById('detailKembalian').innerText = 'Rp. ' + new Intl.NumberFormat('id-ID').format(laporan.penjualan.kembalian);

            const produkList = document.getElementById('detailProdukList');
            produkList.innerHTML = '';

            const produkItem = document.createElement('li');
            produkItem.innerText = `${laporan.produk.name} - ${laporan.jumlah_terjual} x Rp. ${new Intl.NumberFormat('id-ID').format(laporan.produk.harga_jual)}`;
            produkList.appendChild(produkItem);
        });
    });
</script> --}}

@extends('layouts.mainPimpinan')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Laporan Penjualan</h4>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <input type="date" id="filterTanggal" class="form-control w-25" placeholder="Filter Tanggal">

    <!-- Tombol untuk memicu modal -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#filterModal">
        Download PDF
    </button>

    <!-- Modal Filter Tanggal -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">Filter Laporan Penjualan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="downloadForm">
                        <label for="tanggal">Pilih Tanggal:</label>
                        <input type="date" id="tanggal" name="tanggal" class="form-control">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="downloadPDF">Download</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('downloadPDF').addEventListener('click', function() {
            var tanggal = document.getElementById('tanggal').value;
            var url = "{{ route('laporan.download') }}";

            if (tanggal) {
                url += "?tanggal=" + tanggal;
            }

            window.location.href = url; // Redirect ke route download dengan filter
        });
    </script>

                    </div>
                    <table id="laporanTable" class="table align-items-center mb-0 table-striped table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>No</th>
                                <th>No. Transaksi</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody id="laporanTableBody">
                            @php $groupedLaporans = $laporans->groupBy('penjualan_id'); @endphp
                            @foreach ($groupedLaporans as $penjualan_id => $items)
                                @php
                                    $firstItem = $items->first();
                                @endphp
                                <tr data-bs-toggle="modal" data-bs-target="#detailPenjualanModal" data-penjualan="{{ json_encode($items) }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $firstItem->penjualan->no_transaksi }}</td>
                                    <td>{{ $firstItem->penjualan->created_at->format('d-m-Y') }}</td>
                                    <td>Rp. {{ number_format($firstItem->penjualan->total_harga, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Penjualan -->
<div class="modal fade" id="detailPenjualanModal" tabindex="-1" aria-labelledby="detailPenjualanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailPenjualanModalLabel">Detail Penjualan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row mb-2">
                        <div class="col-4"><strong>No. Transaksi</strong></div>
                        <div class="col-1 text-center"><strong>:</strong></div>
                        <div class="col-7" id="detailNoTransaksi"></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4"><strong>Nama Pelanggan</strong></div>
                        <div class="col-1 text-center"><strong>:</strong></div>
                        <div class="col-7" id="detailNamaPelanggan"></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4"><strong>Tanggal</strong></div>
                        <div class="col-1 text-center"><strong>:</strong></div>
                        <div class="col-7" id="detailTanggal"></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4"><strong>Barang</strong></div>
                        <div class="col-1 text-center"><strong>:</strong></div>
                        <div class="col-7">
                            <ul id="detailProdukList" class="list-unstyled mb-0"></ul>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4"><strong>Total</strong></div>
                        <div class="col-1 text-center"><strong>:</strong></div>
                        <div class="col-7" id="detailTotal"></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4"><strong>Uang Pelanggan</strong></div>
                        <div class="col-1 text-center"><strong>:</strong></div>
                        <div class="col-7" id="detailUangAnda"></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4"><strong>Kembalian</strong></div>
                        <div class="col-1 text-center"><strong>:</strong></div>
                        <div class="col-7" id="detailKembalian"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="js/scripts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="assets/demo/chart-area-demo.js"></script>
<script src="assets/demo/chart-bar-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
<script src="js/datatables-simple-demo.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterTanggal = document.getElementById('filterTanggal');
        const laporanTableBody = document.getElementById('laporanTableBody');
        const tableRows = Array.from(laporanTableBody.rows);

        filterTanggal.addEventListener('change', function() {
            filterTable();
        });

        function formatDate(dateString) {
            const dateObj = new Date(dateString);
            if (isNaN(dateObj)) return null;
            const day = String(dateObj.getDate()).padStart(2, '0');
            const month = String(dateObj.getMonth() + 1).padStart(2, '0');
            const year = dateObj.getFullYear();
            return `${day}-${month}-${year}`;
        }

        function filterTable() {
            const selectedDate = filterTanggal.value;
            const formattedDate = formatDate(selectedDate);

            tableRows.forEach(row => {
                const rowDate = row.cells[2].innerText.trim();
                if (!selectedDate || rowDate === formattedDate) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }

        const detailPenjualanModal = document.getElementById('detailPenjualanModal');
        detailPenjualanModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const penjualan = JSON.parse(button.getAttribute('data-penjualan'));

            document.getElementById('detailNoTransaksi').innerText = penjualan[0].penjualan.no_transaksi;
            document.getElementById('detailNamaPelanggan').innerText = penjualan[0].penjualan.user.name;
            document.getElementById('detailTanggal').innerText = new Date(penjualan[0].penjualan.created_at).toLocaleDateString('id-ID');
            document.getElementById('detailTotal').innerText = 'Rp. ' + new Intl.NumberFormat('id-ID').format(penjualan[0].penjualan.total_harga);
            document.getElementById('detailUangAnda').innerText = 'Rp. ' + new Intl.NumberFormat('id-ID').format(penjualan[0].penjualan.uang_pelanggan);
            document.getElementById('detailKembalian').innerText = 'Rp. ' + new Intl.NumberFormat('id-ID').format(penjualan[0].penjualan.kembalian);

            const produkList = document.getElementById('detailProdukList');
            produkList.innerHTML = '';

            penjualan.forEach(detail => {
                const produkItem = document.createElement('li');
                produkItem.innerText = `${detail.produk.name} - ${detail.jumlah_terjual} x Rp. ${new Intl.NumberFormat('id-ID').format(detail.produk.harga_jual)}`;
                produkList.appendChild(produkItem);
            });
        });
    });
</script>
