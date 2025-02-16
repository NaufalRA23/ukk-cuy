<?php

namespace Database\Seeders;

use App\Models\Laporan;
use App\Models\Penjualan;
use App\Models\produk;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LaporanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil beberapa penjualan dan produk untuk contoh data
        $penjualans = Penjualan::all();
        $produks = produk::all();

        // Buat data laporan contoh
        foreach ($penjualans as $penjualan) {
            foreach ($produks as $produk) {
                $jumlahTerjual = rand(1, 10); // Contoh jumlah terjual
                $hargaJual = $produk->harga_jual;
                $hargaBeli = $produk->harga_beli;
                $keuntungan = ($hargaJual - $hargaBeli) * $jumlahTerjual;

                Laporan::create([
                    'penjualan_id' => $penjualan->id,
                    'produk_id' => $produk->id,
                    'jumlah_terjual' => $jumlahTerjual,
                    'keuntungan' => $keuntungan,
                ]);
            }
        }
    }
}
