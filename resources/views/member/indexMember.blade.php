@extends('layouts.mainMember')

@section('title', 'Toko Laptop')

@section('content')
<div class="container py-4">
    <h1></h1>
    <h2 class="text-center mb-4"> </h2>

    <div class="row">
        <p></p>
        <h2 class="text-center mb-4">Daftar Produk Laptop</h2>

        @foreach ($products as $product)
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm h-100">
                    @if (!empty($product->image) && file_exists(public_path($product->image)))
                        <img src="{{ asset($product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                    @else
                        <img src="{{ asset('assets/img/nophoto.jpg') }}" class="card-img-top" alt="No Photo" style="height: 200px; object-fit: cover;">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="text-muted">Merek: <strong>{{ $product->jenis }}</strong></p>
                        <p class="text-truncate">{{ Str::limit($product->deskripsi, 50, '...') }}</p>
                        <p class="text-success fw-bold">Rp {{ number_format($product->harga_jual, 0, ',', '.') }}</p>
                        <p class="text-muted">Stok: {{ $product->stok }}</p>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#detailProductModal{{ $product->id }}">
                            Lihat Detail
                        </button>
                    </div>
                </div>
            </div>

            <!-- Detail Modal -->
            <div class="modal fade" id="detailProductModal{{ $product->id }}" tabindex="-1" aria-labelledby="detailProductModalLabel{{ $product->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="detailProductModalLabel{{ $product->id }}">Detail Produk</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body img">
                            @if (!empty($product->image) && file_exists(public_path($product->image)))
                            <img src="{{ asset($product->image) }}" class="img-fluid rounded mb-3" alt="{{ $product->name }}">
                        @else
                            <img src="{{ asset('assets/img/nophoto.jpg') }}" class="img-fluid rounded mb-3" alt="No Photo">
                        @endif
                            <h5>{{ $product->name }}</h5>
                            <p class="text-muted">Merek: <strong>{{ $product->jenis }}</strong></p>
                            <p>{{ $product->deskripsi }}</p>
                            <p class="text-success fw-bold">Harga: Rp {{ number_format($product->harga_jual, 0, ',', '.') }}</p>
                            <p class="text-muted">Stok: {{ $product->stok }}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
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
