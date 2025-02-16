<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Http\Requests\StorePenjualanRequest;
use App\Http\Requests\UpdatePenjualanRequest;
use App\Models\Laporan;
use App\Models\produk;
use App\Models\User;
use Illuminate\Http\Request;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penjualans = Penjualan::with('user')->get();
        $users = User::where('role_id', 3)->get();

        return view('kasir.penjualanKasir', compact('penjualans', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'produk_id' => 'required|array',
            'produk_id.*' => 'exists:produks,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'integer|min:1',
            'uang_pelanggan' => 'required|numeric|min:0',
        ]);

        $noTransaksi = 'NRA-' . time() . '-' . mt_rand(1000, 9999); // Generate nomor transaksi

        $produkDetails = [];
        $totalHarga = 0;
        $produkToUpdate = [];

        foreach ($request->produk_id as $key => $produkId) {
            $produk = Produk::find($produkId);

            if (!$produk) {
                return redirect()->back()->with('error', "Produk tidak ditemukan.");
            }

            $jumlah = $request->jumlah[$key];

            // Cek jika stok 0
            if ($produk->stok == 0) {
                return redirect()->back()->with('error', "Produk {$produk->name} tidak tersedia (stok habis).");
            }

            // Cek jika stok kurang dari permintaan
            if ($produk->stok < $jumlah) {
                return redirect()->back()->with('error', "Stok untuk {$produk->name} tidak mencukupi. Stok tersisa: {$produk->stok}.");
            }

            $subtotal = $jumlah * $produk->harga_jual;

            $produkDetails[] = [
                'id' => $produk->id,
                'nama' => $produk->name,
                'jumlah' => $jumlah,
                'harga' => $produk->harga_jual
            ];

            $totalHarga += $subtotal;

            // Tandai produk yang stoknya akan dikurangi
            $produkToUpdate[] = ['produk' => $produk, 'jumlah' => $jumlah];
        }

        // Validasi uang pelanggan setelah semua produk dicek
        if ($request->uang_pelanggan < $totalHarga) {
            return redirect()->back()->with('error', 'Uang pelanggan tidak boleh kurang dari total harga.');
        }

        $kembalian = $request->uang_pelanggan - $totalHarga;

        // Kurangi stok produk setelah semua validasi berhasil
        foreach ($produkToUpdate as $item) {
            $item['produk']->stok -= $item['jumlah'];
            $item['produk']->save();
        }

        // Simpan transaksi penjualan
        $penjualan = Penjualan::create([
            'user_id' => $request->user_id,
            'no_transaksi' => $noTransaksi,
            'produk_details' => json_encode($produkDetails),
            'total_harga' => $totalHarga,
            'uang_pelanggan' => $request->uang_pelanggan,
            'kembalian' => $kembalian,
        ]);

        foreach ($request->produk_id as $index => $produkId) {
            $jumlahTerjual = $request->jumlah[$index];
            $hargaJual = Produk::find($produkId)->harga_jual;
            $hargaBeli = Produk::find($produkId)->harga_beli;
            $keuntungan = ($hargaJual - $hargaBeli) * $jumlahTerjual;

            // Simpan data laporan
            Laporan::create([
                'penjualan_id' => $penjualan->id,
                'produk_id' => $produkId,
                'jumlah_terjual' => $jumlahTerjual,
                'keuntungan' => $keuntungan,
            ]);
        }

        return redirect()->back()->with('success', 'Penjualan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Penjualan $penjualan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Penjualan $penjualan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePenjualanRequest $request, Penjualan $penjualan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penjualan $penjualan)
    {
        //
    }
}
