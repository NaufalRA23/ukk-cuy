<?php

namespace Database\Seeders;

use App\Models\Penjualan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $noTransaksi = 'NRA-' . time() . '-' . mt_rand(1000, 9999);

        Penjualan::create([
            'user_id' => 1,
            'no_transaksi' => $noTransaksi,
            'produk_details' => json_encode([
                [
                    'id' => 1,
                    'nama' => 'Produk A',
                    'jumlah' => 2,
                    'harga' => 50000
                ],
                [
                    'id' => 4,
                    'nama' => 'Produk B',
                    'jumlah' => 1,
                    'harga' => 7000
                ]
            ]),
            'total_harga' => 57000
        ]);
    }
}
