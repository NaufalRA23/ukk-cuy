<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('produks')->insert([
            [
                'name' => 'Produk A',
                'deskripsi' => 'Deskripsi produk A.',
                'jenis' => 'Elektronik',
                'image' => null,
                'stok' => 10,
                'harga_beli' => 50000,
                'harga_jual' => 75000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Produk B',
                'deskripsi' => 'Deskripsi produk B.',
                'jenis' => 'Pakaian',
                'image' => null,
                'stok' => 20,
                'harga_beli' => 30000,
                'harga_jual' => 50000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Produk C',
                'deskripsi' => 'Deskripsi produk C.',
                'jenis' => 'Aksesoris',
                'image' => null,
                'stok' => 15,
                'harga_beli' => 20000,
                'harga_jual' => 40000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
