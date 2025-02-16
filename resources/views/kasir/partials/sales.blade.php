<table id="dataTableSales" class="table align-items-center mb-0 table-striped table-bordered table-hover">
    <thead class="thead-dark">
        <tr>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No. Transaksi</th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tanggal</th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($sales as $sale)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td class="text-left">{{ $sale->no_transaksi }}</td>
                <td class="text-left">{{ $sale->created_at->format('d-m-Y')  }}</td>
                <td class="text-left">Rp. {{ number_format($sale->total_harga, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
<div class="d-flex justify-content-center mt-3">
    {{ $sales->links() }}
</div>
