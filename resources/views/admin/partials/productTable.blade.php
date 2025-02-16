<!-- filepath: /c:/laragon/www/tokolaptop/resources/views/admin/partials/productTable.blade.php -->
@foreach ($products as $index => $product)
    <tr>
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
