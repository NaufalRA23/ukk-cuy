@extends('layouts.mainMember')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Transaksi Saya</h4>
                    <div>
                        <input type="text" id="searchTransaksi" class="form-control me-2" placeholder="Cari No. Transaksi">
                    </div>
                    <div>
                        <label for="filterTanggal" class="me-2"><strong>Filter Tanggal:</strong></label>
                        <input type="date" id="filterTanggal" class="form-control">
                    </div>
                </div>
                <div class="card-body">
                    <table id="dataTable" class="table align-items-center mb-0 table-striped table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>No</th>
                                <th>No. Transaksi</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            @foreach ($penjualans as $penjualan)
                                <tr data-bs-toggle="modal" data-bs-target="#detailPenjualanModal" data-penjualan="{{ json_encode($penjualan) }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="no-transaksi">{{ $penjualan->no_transaksi }}</td>
                                    <td class="tanggal-transaksi">{{ $penjualan->created_at->format('Y-m-d') }}</td>
                                    <td>Rp. {{ number_format($penjualan->total_harga, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Transaksi -->
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
                        <div class="col-4"><strong>Uang Anda</strong></div>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var detailPenjualanModal = document.getElementById('detailPenjualanModal');
        var filterTanggal = document.getElementById('filterTanggal');
        var searchTransaksi = document.getElementById('searchTransaksi');
        var tableRows = document.querySelectorAll('#tableBody tr');

        // Event listener untuk modal detail transaksi
        detailPenjualanModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var penjualan = JSON.parse(button.getAttribute('data-penjualan'));

            document.getElementById('detailNoTransaksi').innerText = penjualan.no_transaksi;
            document.getElementById('detailTanggal').innerText = new Date(penjualan.created_at).toLocaleDateString('id-ID');
            document.getElementById('detailTotal').innerText = 'Rp. ' + new Intl.NumberFormat('id-ID').format(penjualan.total_harga);
            document.getElementById('detailUangAnda').innerText = 'Rp. ' + new Intl.NumberFormat('id-ID').format(penjualan.uang_pelanggan);
            document.getElementById('detailKembalian').innerText = 'Rp. ' + new Intl.NumberFormat('id-ID').format(penjualan.kembalian);

            var produkList = document.getElementById('detailProdukList');
            produkList.innerHTML = '';

            JSON.parse(penjualan.produk_details).forEach(function (produk) {
                var li = document.createElement('li');
                li.innerText = `${produk.nama} - ${produk.jumlah} x Rp. ${new Intl.NumberFormat('id-ID').format(produk.harga)}`;
                produkList.appendChild(li);
            });
        });

        // Event listener untuk filter tanggal
        filterTanggal.addEventListener('change', function () {
            filterTable();
        });

        // Event listener untuk pencarian No. Transaksi
        searchTransaksi.addEventListener('input', function () {
            filterTable();
        });

        function filterTable() {
            var selectedDate = filterTanggal.value;
            var searchText = searchTransaksi.value.toLowerCase();

            tableRows.forEach(function (row) {
                var noTransaksi = row.querySelector('.no-transaksi').textContent.toLowerCase();
                var tanggalTransaksi = row.querySelector('.tanggal-transaksi').textContent;

                var matchTanggal = selectedDate === "" || tanggalTransaksi === selectedDate;
                var matchNoTransaksi = searchText === "" || noTransaksi.includes(searchText);

                if (matchTanggal && matchNoTransaksi) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="js/scripts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="assets/demo/chart-area-demo.js"></script>
<script src="assets/demo/chart-bar-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
<script src="js/datatables-simple-demo.js"></script>
