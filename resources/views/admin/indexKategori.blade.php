@extends('layouts.mainAdmin')

@section('title', 'Index Kategori')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-12">
            <h1>Index Kategori</h1>
        </div>
    </div>

    <!-- Alert Notification -->
    <div id="alertSuccess" class="alert alert-success d-none"></div>

    <!-- Button Tambah Kategori -->
    <div class="row mb-3">
        <div class="col-md-12">
            <button id="tambahKategoriBtn" class="btn btn-primary">Tambah Kategori</button>
        </div>
    </div>

    <!-- Tabel Kategori -->
    <div class="row">
        <div class="col-md-12">
            <table class="table table-hover" id="kategoriTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kategoris as $kategori)
                        <tr id="row-{{ $kategori->id }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $kategori->name }}</td>
                            <td>{{ $kategori->deskripsi }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Kategori -->
<div class="modal fade" id="modalKategori" tabindex="-1" aria-labelledby="modalKategoriLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalKategoriLabel">Tambah Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="kategoriForm">
                    @csrf
                    <div class="mb-3">
                        <label for="nameKategori" class="form-label">Nama Kategori</label>
                        <input type="text" class="form-control" id="nameKategori" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsiKategori" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsiKategori" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

<!-- jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        var modalKategori = new bootstrap.Modal(document.getElementById('modalKategori'));

        // Tampilkan modal untuk tambah kategori
        $('#tambahKategoriBtn').click(function() {
            $('#modalKategoriLabel').text('Tambah Kategori');
            $('#kategoriForm')[0].reset();  // Reset form sebelum menampilkan modal
            modalKategori.show();  // Menampilkan modal
        });

        // Simpan kategori (tambah)
        $('#kategoriForm').submit(function(e) {
            e.preventDefault();
            let name = $('#nameKategori').val();
            let deskripsi = $('#deskripsiKategori').val();

            $.ajax({
                url: `/kategori`,
                type: 'POST',
                data: { name, deskripsi, _token: '{{ csrf_token() }}' },
                success: function(response) {
                    modalKategori.hide();  // Sembunyikan modal setelah berhasil
                    $('#alertSuccess').removeClass('d-none').text(response.message);  // Tampilkan pesan sukses
                    setTimeout(() => $('#alertSuccess').addClass('d-none'), 3000);  // Sembunyikan alert setelah 3 detik
                    location.reload();  // Refresh halaman untuk menampilkan data terbaru
                },
                error: function(xhr) {
                    alert('Terjadi kesalahan: ' + xhr.responseText);  // Tampilkan alert jika ada kesalahan
                }
            });
        });
    });
</script>

