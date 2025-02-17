<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Http\Requests\StoreKategoriRequest;
use App\Http\Requests\UpdateKategoriRequest;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::all();
        return view('admin.indexKategori', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'deskripsi' => 'required|string'
        ]);

        Kategori::create($request->all());

        return response()->json(['message' => 'Kategori berhasil ditambahkan!']);
    }

    public function edit($id)
    {
        $kategori = Kategori::findOrFail($id);
        return response()->json($kategori);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'deskripsi' => 'required|string'
        ]);

        $kategori = Kategori::findOrFail($id);
        $kategori->update($request->all());

        return response()->json(['message' => 'Kategori berhasil diperbarui!']);
    }

    public function destroy($id)
    {
        Kategori::findOrFail($id)->delete();
        return response()->json(['message' => 'Kategori berhasil dihapus!']);
    }
}

    /**
     * Store a newly created resource in storage.
     */

