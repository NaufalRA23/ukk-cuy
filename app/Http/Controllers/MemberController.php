<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    public function index()
    {
        $products = produk::all();
        return view('member.indexMember', compact('products'));
    }

    public function transaksi()
    {
        $userId = Auth::id(); // Dapatkan ID user yang sedang login
        $penjualans = Penjualan::where('user_id', $userId)->get(); // Ambil data penjualan berdasarkan user_id

        return view('member.transMember', compact('penjualans'));
    }
}
