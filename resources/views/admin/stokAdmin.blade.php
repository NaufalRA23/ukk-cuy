<!-- filepath: /c:/laragon/www/tokolaptop/resources/views/admin/stokAdmin.blade.php -->
@extends('layouts.mainAdmin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container py-4">
    <h1>.</h1>
    <h2 class="text-center mb-4"> </h2>
    <div class="d-flex justify-content-between mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProductModal">Tambah Produk</button>
        <div class="d-flex ml-auto gap-2">
            <input type="text" id="searchInput" class="form-control w-55" placeholder="Cari Nama Produk...">
            {{-- <button class="btn btn-secondary" id="sortStockBtn" onclick="sortTableByStock()">
                Urutkan Stok <i class="fas fa-sort"></i>
            </button> --}}
        </div>

    </div>
    <div class="row">
        <div class="col-12">
            <p></p>
            <div class="table-responsive p-0">
                <table id="dataTable" class="table align-items-center mb-0 table-striped table-bordered table-hover" data-sort="none">
                    <thead class="thead-dark">
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nama Produk</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Deskripsi</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Merek</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Harga Beli</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Harga Jual</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Stok</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Gambar</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Aksi</th>  
                        </tr>
                    </thead>
                    <tbody id="productTableBody">
                        @foreach ($products as $index => $product)
                            <tr data-index="{{ $loop->iteration }}">
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-left">{{ $product->name }}</td>
                                <td class="text-left">{{ Str::limit($product->deskripsi, 25, '...') }}</td>
                                <td class="text-left">{{ $product->jenis }}</td>
                                <td class="text-left">{{ $product->harga_beli }}</td>
                                <td class="text-left">{{ $product->harga_jual }}</td>
                                <td class="text-left">{{ $product->stok }}</td>
                                <td>
                                    @if (!empty($product->image) && file_exists(public_path($product->image)))
                                    <img src="{{ asset($product->image) }}" alt="{{ $product->nama }}" class="rounded" style="width: 100%; max-width: 100px; height: auto;">
                                @else
                                    <img src="{{ asset('assets/img/nophoto.jpg') }}" alt="No Photo" class="rounded" style="width: 100%; max-width: 100px; height: auto;">
                                @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm" onclick="openEditModal('{{ $product->id }}', '{{ $product->name }}', '{{ $product->deskripsi }}', '{{ $product->jenis }}', '{{ $product->stok }}', '{{ $product->harga_beli }}', '{{ $product->harga_jual }}', '{{ $product->image }}')">
                                        Edit
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="openDeleteModal('{{ $product->id }}', '{{ $product->name }}')">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Add pagination links -->
            <div class="d-flex justify-content-center mt-3" id="paginationLinks">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Create Data -->
<div class="modal fade" id="createProductModal" tabindex="-1" aria-labelledby="createProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createProductModalLabel">Tambah Produk Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return validateCreateProductForm()">
                    @csrf
                    <div class="mb-3">
                        <label for="productName" class="form-label">Nama Produk</label>
                        <input type="text" class="form-control" id="productName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="productDescription" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="productDescription" name="deskripsi" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="productBrand" class="form-label">Merek</label>
                        <select class="form-control" id="productBrand" name="jenis" onchange="toggleCreateBrandInput()" required>
                            <option value="" disabled selected>Pilih Merek</option>
                            <option value="Lenovo">Lenovo</option>
                            <option value="Asus">Asus</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                        <!-- Inputan untuk "Lainnya" -->
                        <input type="text" class="form-control mt-2" id="productBrandInput" name="jenis"
                               placeholder="Masukkan Merek" style="display: none;">
                        <small class="text-danger" id="productBrandError" style="display: none;">
                            Silakan isi merek jika memilih 'Lainnya'.
                        </small>
                    </div>
                    <div class="mb-3">
                        <label for="purchasePrice" class="form-label">Harga Beli</label>
                        <input type="number" class="form-control" id="purchasePrice" name="harga_beli" min="0" required
                            oninput="sellingPrice.setAttribute('min', this.value > 0 ? parseInt(this.value) + 1 : 1)">
                    </div>
                    <div class="mb-3">
                        <label for="sellingPrice" class="form-label">Harga Jual</label>
                        <input type="number" class="form-control" id="sellingPrice" name="harga_jual" required min="1">
                    </div>

                    <div class="mb-3">
                        <label for="productStock" class="form-label">Stok</label>
                        <input type="number" class="form-control" id="productStock" name="stok" min="0"  required>
                    </div>
                    <div class="mb-3">
                        <label for="productImage" class="form-label">Gambar</label>
                        <input type="file" class="form-control" id="productImage" name="image">
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Data -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">Edit Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editProductForm" method="POST" enctype="multipart/form-data" onsubmit="return validateUpdateProductForm()">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_productId" name="id">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Nama Produk</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_jenis" class="form-label">Merek</label>
                        <select class="form-control" id="edit_jenis" name="jenis" onchange="toggleEditBrandInput()" required>
                            <option value="" disabled selected>Pilih Merek</option>
                            <option value="Lenovo">Lenovo</option>
                            <option value="Asus">Asus</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                        <!-- Inputan untuk "Lainnya" -->
                        <input type="text" class="form-control mt-2" id="editProductBrandInput" name="jenis"
                               placeholder="Masukkan Merek" style="display: none;">
                        <small class="text-danger" id="editProductBrandError" style="display: none;">
                            Silakan isi merek jika memilih 'Lainnya'.
                        </small>
                    </div>
                    <div class="mb-3">
                        <label for="edit_harga_beli" class="form-label">Harga Beli</label>
                        <input type="number" class="form-control" id="edit_harga_beli" name="harga_beli" min="0" required
                            oninput="document.getElementById('edit_harga_jual').setAttribute('min', this.value > 0 ? parseInt(this.value) + 1 : 1)">
                    </div>
                    <div class="mb-3">
                        <label for="edit_harga_jual" class="form-label">Harga Jual</label>
                        <input type="number" class="form-control" id="edit_harga_jual" name="harga_jual" required min="1">
                    </div>
                    <div class="mb-3">
                        <label for="edit_stok" class="form-label">Stok</label>
                        <input type="number" class="form-control" id="edit_stok" name="stok" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_image" class="form-label">Gambar</label>
                        <input type="file" class="form-control" id="edit_image" name="image">
                        <div id="edit_imagePreview" class="mt-2"></div>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Delete Data -->
<div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteProductModalLabel">Hapus Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus produk <strong id="deleteProductName"></strong>?</p>
            </div>
            <div class="modal-footer">
                <form id="deleteProductForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Search dan filter data produk
       document.getElementById('searchInput').addEventListener('keyup', function() {
        var query = this.value;

        fetch(`/search-products?query=${query}`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('productTableBody').innerHTML = html;
            });
    });

    // Fungsi utama untuk mengurutkan tabel berdasarkan stok
    function sortTableByStock() {
        var table = document.getElementById('dataTable');
        var sortOrder = table.getAttribute('data-sort');

        if (sortOrder === 'none') {
            sortOrder = 'asc';
        } else if (sortOrder === 'asc') {
            sortOrder = 'desc';
        } else {
            sortOrder = 'none';
        }

        fetch(`/filter-products?sortOrder=${sortOrder}`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('productTableBody').innerHTML = html;
                table.setAttribute('data-sort', sortOrder);
                updateSortText(sortOrder === 'asc' ? 'Stok Sedikit' : sortOrder === 'desc' ? 'Stok Terbanyak' : 'Urutkan Stok', sortOrder === 'asc' ? 'fa-sort-down' : sortOrder === 'desc' ? 'fa-sort-up' : 'fa-sort');
            });
    }

// Fungsi untuk mengganti teks dan ikon dengan benar
function updateSortText(newText, iconClass) {
    var sortButton = document.querySelector('#sortStockBtn');
    if (sortButton) {
        sortButton.innerHTML = `${newText} <i class="fas ${iconClass}"></i>`;
    }
}

// Simpan urutan awal baris agar bisa dikembalikan saat reset
document.addEventListener('DOMContentLoaded', function () {
    var rows = document.querySelectorAll('#dataTable tbody tr');
    rows.forEach((row, index) => row.setAttribute('data-index', index));
});
    // Tambah Data Produk
    function toggleCreateBrandInput() {
        var select = document.getElementById('productBrand');
        var input = document.getElementById('productBrandInput');

        if (select.value === "Lainnya") {
            input.style.display = "block";
            input.setAttribute("required", "required");
        } else {
            input.style.display = "none";
            input.removeAttribute("required");
            input.value = select.value; // Pastikan tetap memiliki nilai
        }
    }

    function validateCreateProductForm() {
        var select = document.getElementById('productBrand');
        var input = document.getElementById('productBrandInput');
        var errorText = document.getElementById('productBrandError');

        if (select.value === "Lainnya") {
            if (input.value.trim() === "") {
                errorText.style.display = "block";
                return false; // Mencegah submit jika kosong
            } else {
                errorText.style.display = "none";
                // Masukkan nilai input ke dalam select agar dikirim dalam form
                select.value = input.value.trim();
            }
        }

        return true; // Izinkan form submit jika valid
    }

    // Edit data produk
    function toggleEditBrandInput() {
        var select = document.getElementById('edit_jenis');
        var input = document.getElementById('editProductBrandInput');

        if (select.value === "Lainnya") {
            input.style.display = "block";
            input.setAttribute("required", "required");
        } else {
            input.style.display = "none";
            input.removeAttribute("required");
            input.value = select.value; // Pastikan tetap memiliki nilai
        }
    }

    function validateUpdateProductForm() {
        var select = document.getElementById('edit_jenis');
        var input = document.getElementById('editProductBrandInput');
        var errorText = document.getElementById('editProductBrandError');

        if (select.value === "Lainnya") {
            if (input.value.trim() === "") {
                errorText.style.display = "block";
                return false; // Mencegah submit jika kosong
            } else {
                errorText.style.display = "none";
                // Masukkan nilai input ke dalam select agar dikirim dalam form
                select.value = input.value.trim();
            }
        }

        var hargaBeli = parseInt(document.getElementById('edit_harga_beli').value);
        var hargaJual = parseInt(document.getElementById('edit_harga_jual').value);

        if (hargaJual <= hargaBeli) {
            alert("Harga jual harus lebih besar dari harga beli.");
            return false; // Mencegah submit jika harga jual kurang dari atau sama dengan harga beli
        }

        return true; // Izinkan form submit jika valid
    }

    function openEditModal(id, name, deskripsi, jenis, stok, harga_beli, harga_jual, image) {
        document.getElementById('editProductForm').action = "/products/" + id;
        document.getElementById('edit_productId').value = id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_deskripsi').value = deskripsi;
        document.getElementById('edit_jenis').value = jenis;
        document.getElementById('edit_stok').value = stok;
        document.getElementById('edit_harga_beli').value = harga_beli;
        document.getElementById('edit_harga_jual').value = harga_jual;
        document.getElementById('edit_harga_jual').setAttribute('min', parseInt(harga_beli) + 1);

        // Set the value of the select element
        var select = document.getElementById('edit_jenis');
        var input = document.getElementById('editProductBrandInput');
        select.value = jenis;

        // Show or hide the input field based on the selected value
        if (jenis === "Lainnya") {
            input.style.display = "block";
            input.setAttribute("required", "required");
            input.value = jenis;
        } else {
            input.style.display = "none";
            input.removeAttribute("required");
            input.value = jenis;
        }

        const imagePreview = document.getElementById('edit_imagePreview');
        imagePreview.innerHTML = image ? `<img src="/${image}" alt="Produk Image" width="100">` : "";

        new bootstrap.Modal(document.getElementById('editProductModal')).show();
    }

    // Hapus Data Produk
    function openDeleteModal(id, name) {
        document.getElementById('deleteProductForm').action = "/products/" + id;
        document.getElementById('deleteProductName').innerText = name;
        new bootstrap.Modal(document.getElementById('deleteProductModal')).show();
    }
</script>

@endsection

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="js/scripts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="assets/demo/chart-area-demo.js"></script>
<script src="assets/demo/chart-bar-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
<script src="js/datatables-simple-demo.js"></script>
