<?php

namespace App\Http\Controllers;

use App\Models\produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = produk::orderBy('created_at', 'desc')->paginate(5);
        return view('admin.stokAdmin', compact('products'));
    }

    public function filter(Request $request)
    {
        $sortOrder = $request->input('sortOrder', 'none'); // Default ke none
        if ($sortOrder === 'none') {
            $products = produk::orderBy('created_at', 'desc')->paginate(5);
        } else {
            $products = produk::orderBy('stok', $sortOrder)->orderBy('created_at', 'desc')->paginate(5);
        }

        return view('admin.partials.productTable', compact('products'))->render();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('produk.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'jenis' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg',
            'stok' => 'required|integer',
            'harga_beli' => 'required|integer',
            'harga_jual' => 'required|integer',
        ]);
        $data = $request->all();

        if ($request->hasFile('image')) {
            $fileName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('image'), $fileName);
            $data['image'] = 'image/' . $fileName;
        }

        produk::create($data);

        return redirect()->route('index.admin')->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(produk $produk)
    {
        return response()->json($produk);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $products = produk::where('name', 'LIKE', "%{$query}%")->paginate(10);

        return view('admin.partials.productTable', compact('products'))->render();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $produk = produk::findOrFail($id);
        return view('produk.edit', compact('produk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $produk = produk::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'jenis' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stok' => 'required|integer',
            'harga_beli' => 'required|integer',
            'harga_jual' => 'required|integer',
        ]);

        $data = $request->except(['image']);

        // Cek apakah ada file gambar yang diunggah
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($produk->image && file_exists(public_path($produk->image))) {
                unlink(public_path($produk->image));
            }

            // Simpan gambar baru
            $fileName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('image'), $fileName);
            $data['image'] = 'image/' . $fileName;
        } else {
            // Jika tidak ada gambar baru, tetap gunakan gambar lama
            $data['image'] = $produk->image;
        }

        $produk->update($data);

        return redirect()->route('index.admin')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $produk = produk::findOrFail($id);

    if ($produk->image) {
        Storage::disk('public')->delete($produk->image);
    }
    $produk->delete();

    return redirect()->route('index.admin')->with('success', 'Produk berhasil dihapus.');
    }
}
