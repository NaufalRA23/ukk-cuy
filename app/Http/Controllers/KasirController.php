<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Penjualan;
use App\Models\produk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KasirController extends Controller
{
    public function penjualan()
    {
        $penjualans = Penjualan::with('user')->get();
        $users = User::where('role_id', 3)->get();
        $produks = produk::all(); // Ambil semua produk

        // Kirim data penjualan ke view
        return view('kasir.penjualanKasir', compact('penjualans', 'users', 'produks'));
    }
    public function index(Request $request)
    {
        $totalMembers = Member::count();
        $totalSales = Penjualan::sum('total_harga'); // Total penjualan

        // Gunakan parameter berbeda untuk masing-masing tabel
        $membersPage = $request->query('members_page', 1);
        $salesPage = $request->query('sales_page', 1);

        // Pagination dengan nama query string khusus
        $members = Member::with('user')->paginate(7, ['*'], 'members_page', $membersPage);
        $sales = Penjualan::paginate(7, ['*'], 'sales_page', $salesPage);

        // Jika request berasal dari AJAX, kembalikan partial view yang sesuai
        if ($request->ajax()) {
            if ($request->has('members_page')) {
                return view('kasir.partials.members', compact('members'))->render();
            } elseif ($request->has('sales_page')) {
                return view('kasir.partials.sales', compact('sales'))->render();
            }
        }

        return view('kasir.indexKasir', compact('totalMembers', 'members', 'totalSales', 'sales'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'gender' => 'required|string',
            'alamat' => 'required|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 3,
        ]);

        Member::create([
            'user_id' => $user->id,
            'gender' => $request->gender,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('kasirM')->with('success', 'Member berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $member = Member::with('user')->findOrFail($id);
        return view('kasir.editMember', compact('member'));
    }

    public function update(Request $request, $id)
    {
        $member = Member::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $member->user->id,
            'gender' => 'required|string',
            'alamat' => 'required|string',
        ]);
        $user = $member->user;

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        $member->update([
            'gender' => $request->gender,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('kasirM')->with('success', 'Member berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $member = Member::findOrFail($id);
        $user = $member->user;

        $member->delete();
        $user->delete();

        return redirect()->route('kasirM')->with('success', 'Member berhasil dihapus.');
    }
}
