<?php

namespace App\Http\Controllers\MasterBackend\SettingRkbu;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingRkbu\KategoriRekening;
use App\Models\MasterBackend\SettingRkbu\SubKategoriRekening;
use Illuminate\Http\Request;

class SubKategoriRekeningController extends Controller
{
    public function index()
    {
        $sub_kategori_rekenings = SubKategoriRekening::with('kategori_rekening')->get();
        $kategori_rekenings = KategoriRekening::all();

        return view(
            'master_backend.setting_rkbu.sub_kategori_rekening.index',
            compact('sub_kategori_rekenings', 'kategori_rekenings'),
            [
                'title' => 'Halaman Sub Kategori Rekening',
                'title2' => 'Sub Kategori Rekening',
                'routePrefix' => 'sub_kategori_rekenings',
            ],
            [
                'title' => 'Halaman Sub Kategori Rekening',
                'title2' => 'Sub Kategori Rekening',
                'routePrefix' => 'sub_kategori_rekenings',
            ],
        );
    }

    public function create()
    {
        $kategori_rekenings = KategoriRekening::all();

        return view(
            'master_backend.setting_rkbu.sub_kategori_rekening.create',
            compact('sub_kategori_rekening', 'kategori_rekenings'),
            [
                'title' => 'Halaman Sub Kategori Rekening',
                'title2' => 'Sub Kategori Rekening',
                'routePrefix' => 'sub_kategori_rekenings',
            ],
        );
    }

    public function store(Request $request)
    {
        SubKategoriRekening::create($this->validatedData($request));

        return redirect()
            ->route('sub_kategori_rekenings.index')
            ->with('success', 'Data Berhasil di Tambahkan !!');
    }

    /**
     * Display the specified resource.
     */
    public function show(SubKategoriRekening $subKategoriRekening)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubKategoriRekening $subKategoriRekening)
    {
        $kategori_rekenings = KategoriRekening::all();
        $sub_kategori_rekening = $subKategoriRekening;

        return view(
            'master_backend.setting_rkbu.sub_kategori_rekening.edit',
            compact('sub_kategori_rekening', 'kategori_rekenings'),
            [
                'title' => 'Halaman Sub Kategori Rekening',
                'title2' => 'Sub Kategori Rekening',
                'routePrefix' => 'sub_kategori_rekenings',
            ],
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubKategoriRekening $subKategoriRekening)
    {
        $subKategoriRekening->update($this->validatedData($request));

        return redirect()
            ->route('sub_kategori_rekenings.index')
            ->with('success', 'Data Berhasil di Update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubKategoriRekening $subKategoriRekening)
    {
        $subKategoriRekening->delete();

        return redirect()
            ->route('sub_kategori_rekenings.index')
            ->with('success', 'Data Berhasil di Hapus');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'kategori_rekening_id' => ['required', 'exists:kategori_rekenings,id'],
            'kode_sub_kategori_rekening' => ['required', 'string', 'max:100'],
            'nama_sub_kategori_rekening' => ['required', 'string'],
        ]);
    }
}
