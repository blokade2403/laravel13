<?php

namespace App\Http\Controllers\MasterBackend\SettingRkbu;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingRkbu\JenisKategoriRkbu;
use App\Models\MasterBackend\SettingRkbu\KategoriRkbu;
use App\Models\MasterBackend\SettingRkbu\ObyekBelanja;
use Illuminate\Http\Request;

class KategoriRkbuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jenis_kategori_rkbus = JenisKategoriRkbu::all();
        $obyek_belanjas = ObyekBelanja::all();
        $kategori_rkbus = KategoriRkbu::with(['obyek_belanja', 'jenis_kategori_rkbu'])->get();

        return view(
            'master_backend.setting_rkbu.kategori_rkbu.index',
            compact('kategori_rkbus', 'jenis_kategori_rkbus', 'obyek_belanjas'),
            [
                'title' => 'Halaman Kategori RKBU',
                'title2' => 'Kategori RKBU',
                'routePrefix' => 'kategori_rkbus',
            ],
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jenis_kategori_rkbus = JenisKategoriRkbu::all();
        $obyek_belanjas = ObyekBelanja::all();

        return view(
            'master_backend.setting_rkbu.kategori_rkbu.create',
            compact('jenis_kategori_rkbus', 'obyek_belanjas'),
            [
                'title' => 'Halaman Kategori RKBU',
                'title2' => 'Kategori RKBU',
                'routePrefix' => 'kategori_rkbus',
            ],
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        KategoriRkbu::create($this->validatedData($request));

        // Redirect ke halaman yang diinginkan dengan pesan sukses
        return redirect()
            ->route('kategori_rkbus.index')
            ->with('success', 'kategori_rkbu berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(KategoriRkbu $kategoriRkbu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KategoriRkbu $kategoriRkbu)
    {
        $kategori_rkbus = $kategoriRkbu->load(['obyek_belanja', 'jenis_kategori_rkbu']);
        $jenis_kategori_rkbus = JenisKategoriRkbu::all();
        $obyek_belanjas = ObyekBelanja::all();

        return view(
            'master_backend.setting_rkbu.kategori_rkbu.edit',
            compact('kategori_rkbus', 'jenis_kategori_rkbus', 'obyek_belanjas'),
            [
                'title' => 'Halaman Kategori RKBU',
                'title2' => 'Kategori RKBU',
                'routePrefix' => 'kategori_rkbus',
            ],
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KategoriRkbu $kategoriRkbu)
    {
        $kategoriRkbu->update($this->validatedData($request));

        return redirect()
            ->route('kategori_rkbus.index')
            ->with('success', 'kategori_rkbu Delete Success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KategoriRkbu $kategoriRkbu)
    {
        $kategoriRkbu->delete();

        return redirect()
            ->route('kategori_rkbus.index')
            ->with('success', 'kategori_rkbu deleted successfully.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'jenis_kategori_rkbu_id' => ['required', 'exists:jenis_kategori_rkbus,id'],
            'obyek_belanja_id' => ['nullable', 'exists:obyek_belanjas,id'],
            'kode_kategori_rkbu' => ['required', 'string', 'max:100'],
            'nama_kategori_rkbu' => ['required', 'string'],
        ]);
    }
}
