<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class produk extends Model
{
    /** @use HasFactory<\Database\Factories\ProdukFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'deskripsi',
        'jenis',
        'image',
        'stok',
        'harga_beli',
        'harga_jual',
    ];

    public function laporans()
    {
        return $this->hasMany(Laporan::class);
    }

    public function kategoti()
    {
        return $this->belongsTo(Kategori::class);
    }
}
