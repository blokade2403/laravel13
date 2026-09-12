<?php

namespace App\Http\Controllers\MasterBackend\SettingRkbu;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingRkbu\JenisBelanja;
use App\Models\MasterBackend\SettingRkbu\JenisKategoriRkbu;
use Illuminate\Http\Request;

class JenisKategoriRkbuController extends Controller
{
    public function index()
    {
        $jenis_kategori_rkbus = JenisKategoriRkbu::with('jenis_belanja')->get();
        $jenis_belanjas = JenisBelanja::all();

        return view(
            'master_backend.setting_rkbu.jenis_kategori_rkbu.index',
            compact('jenis_kategori_rkbus', 'jenis_belanjas'),
            [
                'title' => 'Halaman Jenis Belanja',
                'title2' => 'Jenis Belanja',
                'routePrefix' => 'jenis_kategori_rkbus',
            ],
        );
    }

    public function create()
    {
        $jenis_belanja = JenisBelanja::all();

        return view(
            'master_backend.setting_rkbu.jenis_kategori_rkbu.create',
            compact('jenis_belanja'),
            [
                'title' => 'Halaman Jenis Belanja',
                'title2' => 'Jenis Belanja',
                'routePrefix' => 'jenis_kategori_rkbus',
            ],
        );
    }

    public function store(Request $request)
    {
        JenisKategoriRkbu::create($this->validatedData($request));

        return redirect()
            ->route('jenis_kategori_rkbus.index')
            ->with('success', 'Jenis Kategori Rkbu Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(JenisKategoriRkbu $jenisKategoriRkbu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JenisKategoriRkbu $jenisKategoriRkbu)
    {
        $jenis_belanja = JenisBelanja::all();

        return view(
            'master_backend.setting_rkbu.jenis_kategori_rkbu.edit',
            compact('jenisKategoriRkbu', 'jenis_belanja'),
            [
                'title' => 'Halaman Jenis Belanja',
                'title2' => 'Jenis Belanja',
                'routePrefix' => 'jenis_kategori_rkbus',
            ],
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JenisKategoriRkbu $jenisKategoriRkbu)
    {
        $jenisKategoriRkbu->update($this->validatedData($request));

        return redirect()
            ->route('jenis_kategori_rkbus.index')
            ->with('success', 'Nama level_user Delete Success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JenisKategoriRkbu $jenisKategoriRkbu)
    {
        $jenisKategoriRkbu->delete();

        return redirect()
            ->route('jenis_kategori_rkbus.index')
            ->with('success', 'Data jenis kategori rkbu Berhasil dihapus.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'jenis_belanja_id' => ['required'],
            'kode_jenis_kategori_rkbu' => ['required'],
            'nama_jenis_kategori_rkbu' => ['required'],
        ], [
            'jenis_belanja_id.required' => 'Jenis Belanja wajib diisi.',
            'kode_jenis_kategori_rkbu.required' => 'Kode Jenis Kategori RKBU wajib diisi.',
            'nama_jenis_kategori_rkbu.required' => 'Nama Jenis Kategori RKBU wajib diisi.',
        ]);
    }
}
