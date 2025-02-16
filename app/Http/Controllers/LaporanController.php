<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Http\Requests\StoreLaporanRequest;
use App\Http\Requests\UpdateLaporanRequest;
use App\Models\Member;
use App\Models\Penjualan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

        return view('pimpinan.indexPimpinan', compact('totalMembers', 'members', 'totalSales', 'sales'));
    }

    public function laporan(Request $request)
    {
        $query = Laporan::with(['penjualan.user', 'produk']);

        if ($request->has('tanggal')) {
            $tanggal = $request->input('tanggal');
            $query->whereDate('created_at', $tanggal);
        }

        $laporans = $query->get();
        return view('pimpinan.laporan', compact('laporans'));
    }

    public function downloadPDF(Request $request)
{
    $tanggal = $request->input('tanggal');
    $laporans = Laporan::with('produk');

    // Jika ada filter tanggal, gunakan whereDate
    if ($tanggal) {
        $laporans->whereDate('created_at', $tanggal);
    }

    $laporans = $laporans->get(); // Ambil data dari database

    // Debugging jika data kosong
    if ($laporans->isEmpty()) {
        return back()->with('error', 'Tidak ada data laporan untuk tanggal ini.');
    }

    // Kirim data ke view PDF
    $pdf = Pdf::loadView('laporan.pdf', compact('laporans', 'tanggal'));

    return $pdf->download('laporan_penjualan.pdf');
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
    public function store(StoreLaporanRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Laporan $laporan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Laporan $laporan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLaporanRequest $request, Laporan $laporan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Laporan $laporan)
    {
        //
    }
}
