@extends('layouts.mainKasir')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="row">
                <!-- Card for Total Members -->
                <div class="col-xl-6 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Members</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalMembers }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card for Total Sales -->
                <div class="col-xl-6 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Total Penjualan</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rp. {{ number_format($totalSales, 0, ',', '.') }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6" id="members-table">
            @include('kasir.partials.members')
        </div>
        <div class="col-md-6" id="sales-table">
            @include('kasir.partials.sales')
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).on('click', '#members-table .pagination a', function(e) {
        e.preventDefault();
        var url = new URL($(this).attr('href'));
        url.searchParams.set('sales_page', getParameterByName('sales_page')); // Jaga halaman sales tetap sama

        $.get(url.toString(), function(data) {
            $('#members-table').html(data);
        });
    });

    $(document).on('click', '#sales-table .pagination a', function(e) {
        e.preventDefault();
        var url = new URL($(this).attr('href'));
        url.searchParams.set('members_page', getParameterByName('members_page')); // Jaga halaman members tetap sama

        $.get(url.toString(), function(data) {
            $('#sales-table').html(data);
        });
    });

    // Fungsi untuk mendapatkan nilai parameter dari URL
    function getParameterByName(name) {
        var url = new URL(window.location.href);
        return url.searchParams.get(name) || 1; // Jika tidak ada, default ke 1
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
