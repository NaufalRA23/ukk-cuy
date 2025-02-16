<!-- filepath: /c:/laragon/www/tokolaptop/resources/views/kasir/penjualanKasir.blade.php -->
@extends('layouts.mainKasir')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card-body px-0 pb-2">
                    <div class="d-flex justify-content-between mb-3">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahPenjualanModal">Tambah Penjualan</button>
                        <input type="text" id="searchInput" class="form-control w-25" placeholder="Cari No. Transaksi atau Nama User...">
                        {{-- <input type="date" id="dateFilter" class="form-control w-25"> --}}
                    </div>
                    <div class="table-responsive p-0">
                        <table id="dataTable" class="table align-items-center mb-0 table-striped table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No. Transaksi</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nama Customer</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tanggal</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($penjualans as $penjualan)
                                    <tr data-bs-toggle="modal" data-bs-target="#detailPenjualanModal" data-penjualan="{{ json_encode($penjualan) }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $penjualan->no_transaksi }}</td>
                                        <td>{{ $penjualan->user->name }}</td>
                                        <td>{{ $penjualan->created_at->format('d-m-Y') }}</td>
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

    <!-- Modal Tambah Penjualan -->
    <div class="modal fade" id="tambahPenjualanModal" tabindex="-1" aria-labelledby="tambahPenjualanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahPenjualanModalLabel">Tambah Penjualan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('penjualans.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Nama Customer</label>
                            <select class="form-control select2" id="user_id" name="user_id" required>
                                <option value="">Pilih Customer</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Produk Container -->
                        <div id="produkContainer">
                            <div class="produk-item d-flex align-items-center gap-2">
                                <select class="form-control produk_id" name="produk_id[]" required>
                                    <option value="">Pilih Produk</option>
                                    @foreach($produks as $produk)
                                        <option value="{{ $produk->id }}" data-harga="{{ $produk->harga_jual }}" data-stok="{{ $produk->stok }}">{{ $produk->name }}</option>
                                    @endforeach
                                </select>
                                <input type="text" class="form-control harga_beli" name="harga_beli[]" readonly placeholder="Harga">
                                <input type="number" class="form-control jumlah" name="jumlah[]" min="1" value="0">
                                <button type="button" class="btn btn-danger remove-produk">Hapus</button>
                            </div>
                        </div>

                        <button type="button" id="addProduk" class="btn btn-success my-2">Tambah Produk</button>

                        <div class="mb-3">
                            <label for="total_harga" class="form-label">Total Harga</label>
                            <input type="text" class="form-control" id="total_harga" name="total_harga" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="uang_pelanggan" class="form-label">Uang Pelanggan</label>
                            <input type="number" class="form-control" id="uang_pelanggan" name="uang_pelanggan" required>
                        </div>

                        <div class="mb-3">
                            <label for="kembalian" class="form-label">Kembalian</label>
                            <input type="text" class="form-control" id="kembalian" name="kembalian" readonly>
                        </div>

                        <button type="submit" class="btn btn-primary" id="submitBtn">Submit</button>
                    </form>
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

    <!-- Modal pesan -->
    <div class="modal fade" id="stokModal" tabindex="-1" aria-labelledby="stokModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="stokModalLabel">Peringatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Produk ini tidak tersedia (stok habis).
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    function updateHarga(select) {
        let produkItem = select.closest('.produk-item');
        let hargaBeliInput = produkItem.querySelector('.harga_beli');
        let selectedOption = select.options[select.selectedIndex];
        let hargaJual = selectedOption.getAttribute("data-harga");
        let stok = selectedOption.getAttribute("data-stok");

        if (stok == 0) {
            let stokModal = new bootstrap.Modal(document.getElementById("stokModal"));
            let tambahPenjualanModal = bootstrap.Modal.getInstance(document.getElementById("tambahPenjualanModal"));
            tambahPenjualanModal.hide(); // Tutup modal tambah penjualan
            stokModal.show();

            // Buka kembali modal tambah penjualan setelah modal stok ditutup
            document.getElementById("stokModal").addEventListener('hidden.bs.modal', function () {
                tambahPenjualanModal.show();
            });

            select.value = ""; // Reset pilihan ke default
            hargaBeliInput.value = "";
        } else {
            hargaBeliInput.value = hargaJual ? hargaJual : 0;
        }

        updateTotal();
    }

    function formatDate(inputDate) {
        let parts = inputDate.split("-"); // Pisahkan "YYYY-MM-DD"
        if (parts.length === 3) {
            return `${parts[2]}-${parts[1]}-${parts[0]}`; // Ubah jadi "DD-MM-YYYY"
        }
        return inputDate; // Kembalikan nilai asli jika tidak sesuai format
    }

    function filterTable() {
        let searchValue = searchInput.value.toLowerCase();
        let selectedDate = formatDate(dateFilter.value); // Ubah ke format "DD-MM-YYYY"
        let rows = document.querySelectorAll("#dataTable tbody tr");

        rows.forEach(row => {
            let noTransaksi = row.cells[1].textContent.toLowerCase();
            let namaCustomer = row.cells[2].textContent.toLowerCase();
            let tanggalTransaksi = row.cells[3].textContent.trim(); // Format sudah "DD-MM-YYYY"

            let matchesSearch = noTransaksi.includes(searchValue) || namaCustomer.includes(searchValue);
            let matchesDate = selectedDate === "" || tanggalTransaksi === selectedDate;

            if (matchesSearch && matchesDate) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }

    // Gunakan satu event listener untuk menangani pencarian & filter tanggal
    searchInput.addEventListener("input", filterTable);
    dateFilter.addEventListener("change", filterTable);

    searchInput.addEventListener("input", function() {
        let searchValue = searchInput.value.toLowerCase();
        let rows = document.querySelectorAll("#dataTable tbody tr");

        rows.forEach(row => {
            let noTransaksi = row.cells[1].textContent.toLowerCase();
            let namaCustomer = row.cells[2].textContent.toLowerCase();

            if (noTransaksi.includes(searchValue) || namaCustomer.includes(searchValue)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });

    function updateTotal() {
        let total = 0;
        document.querySelectorAll('.produk-item').forEach(item => {
            let jumlah = parseInt(item.querySelector('.jumlah').value) || 0;
            let harga = parseInt(item.querySelector('.harga_beli').value) || 0;
            total += jumlah * harga;
        });
        document.getElementById("total_harga").value = total;
        updateKembalian();
        checkFormValidity();
    }

    function updateKembalian() {
        let totalHarga = parseFloat(document.getElementById("total_harga").value) || 0;
        let uangPelanggan = parseFloat(document.getElementById("uang_pelanggan").value) || 0;
        let kembalian = uangPelanggan - totalHarga;
        let kembalianInput = document.getElementById("kembalian");

        if (uangPelanggan < totalHarga) {
            kembalianInput.value = "Uang tidak cukup!";
            kembalianInput.classList.add("text-danger");
        } else {
            kembalianInput.value = kembalian;
            kembalianInput.classList.remove("text-danger");
        }
        checkFormValidity();
    }

    function validateStok(input) {
        let produkItem = input.closest('.produk-item');
        let selectedOption = produkItem.querySelector('.produk_id').options[produkItem.querySelector('.produk_id').selectedIndex];
        let stok = parseInt(selectedOption.getAttribute("data-stok")) || 0;
        let jumlah = parseInt(input.value) || 0;

        if (jumlah > stok) {
            input.setCustomValidity("Jumlah melebihi stok yang tersedia!");
            input.reportValidity();
        } else {
            input.setCustomValidity("");
        }
        checkFormValidity();
    }

    function checkFormValidity() {
        let submitButton = document.getElementById("submitBtn");
        let totalHarga = parseFloat(document.getElementById("total_harga").value) || 0;
        let uangPelanggan = parseFloat(document.getElementById("uang_pelanggan").value) || 0;

        let isValid = totalHarga > 0 && uangPelanggan >= totalHarga;
        document.querySelectorAll('.produk-item .jumlah').forEach(input => {
            if (input.validationMessage !== "") {
                isValid = false;
            }
        });

        submitButton.disabled = !isValid;
    }

    document.addEventListener("change", function(event) {
        if (event.target.classList.contains("produk_id")) {
            updateHarga(event.target);
        }
    });

    document.addEventListener("input", function(event) {
        if (event.target.classList.contains("jumlah")) {
            validateStok(event.target);
            updateTotal();
        } else if (event.target.id === "uang_pelanggan") {
            updateKembalian();
        }
    });

    document.getElementById("addProduk").addEventListener("click", function() {
        let produkContainer = document.getElementById("produkContainer");
        let newProduk = document.querySelector('.produk-item').cloneNode(true);

        newProduk.querySelector('.jumlah').value = '';
        newProduk.querySelector('.harga_beli').value = '';

        let removeButton = newProduk.querySelector('.remove-produk');
        if (removeButton) {
            removeButton.addEventListener("click", function() {
                newProduk.remove();
                updateTotal();
            });
        }

        produkContainer.appendChild(newProduk);
    });

    document.addEventListener("click", function(event) {
        if (event.target.classList.contains("remove-produk")) {
            event.target.closest(".produk-item").remove();
            updateTotal();
        }
    });

    checkFormValidity();
});
</script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var detailPenjualanModal = document.getElementById('detailPenjualanModal');
            detailPenjualanModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var penjualan = JSON.parse(button.getAttribute('data-penjualan'));

                document.getElementById('detailNoTransaksi').innerText = penjualan.no_transaksi;
                document.getElementById('detailNamaPelanggan').innerText = penjualan.user.name;
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

            // Inisialisasi Select2
            $('.select2').select2({
                dropdownParent: $('#tambahPenjualanModal')
            });

            // Update harga jual ketika produk dipilih
            $('#produk_id').on('change', function () {
                var selectedOption = $(this).find('option:selected');
                var hargaJual = selectedOption.data('harga');
                $('#harga_beli').val(hargaJual);
            });
        });
    </script>

@endsection

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="js/scripts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="assets/demo/chart-area-demo.js"></script>
<script src="assets/demo/chart-bar-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
<script src="js/datatables-simple-demo.js"></script>
