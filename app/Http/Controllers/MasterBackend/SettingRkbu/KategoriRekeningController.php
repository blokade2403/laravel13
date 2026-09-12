<?php

namespace App\Http\Controllers\MasterBackend\SettingRkbu;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingRkbu\KategoriRekening;
use Illuminate\Http\Request;

class KategoriRekeningController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategori_rekenings = KategoriRekening::all();

        return view('master_backend.setting_rkbu.kategori_rekening.index', compact('kategori_rekenings'), [
            'title' => 'Halaman Kategori Rekening',
            'title2' => 'Kategori Rekening',
            'routePrefix' => 'kategori_rekenings',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master_backend.setting_rkbu.kategori_rekening.create', [
            'title' => 'Halaman Kategori Rekening',
            'title2' => 'Kategori Rekening',
            'routePrefix' => 'kategori_rekenings',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        KategoriRekening::create($this->validatedData($request));

        return redirect()->route('kategori_rekenings.index')->with('success', 'Nama kategori_rekening Delete Success');
    }

    /**
     * Display the specified resource.
     */
    public function show(KategoriRekening $kategoriRekening)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KategoriRekening $kategoriRekening)
    {
        return view('master_backend.setting_rkbu.kategori_rekening.edit', compact('kategoriRekening'), [
            'title' => 'Halaman Kategori Rekening',
            'title2' => 'Kategori Rekening',
            'routePrefix' => 'kategori_rekenings',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KategoriRekening $kategoriRekening)
    {
        $kategoriRekening->update($this->validatedData($request));

        return redirect()->route('kategori_rekenings.index')->with('success', 'Nama level_user Delete Success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KategoriRekening $kategoriRekening)
    {
        $kategoriRekening->delete();

        return redirect()->route('kategori_rekenings.index')
            ->with('success', 'Kategori Rekening berhasil dihapus.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'kode_kategori_rekening' => ['required', 'string', 'max:100'],
            'nama_kategori_rekening' => ['required', 'string'],
        ]);
    }
}
