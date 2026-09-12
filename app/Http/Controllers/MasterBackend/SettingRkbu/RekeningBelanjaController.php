<?php

namespace App\Http\Controllers\MasterBackend\SettingRkbu;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingRkbu\Aktivitas;
use App\Models\MasterBackend\SettingRkbu\RekeningBelanja;
use App\Models\MasterBackend\SettingRkbu\SubKategoriRekening;
use Illuminate\Http\Request;

class RekeningBelanjaController extends Controller
{
    public function index()
    {
        $rekening_belanjas = RekeningBelanja::with('aktivitas')->get();
        $aktivitas = Aktivitas::all();
        $sub_kategori_rekenings = SubKategoriRekening::all();

        return view(
            'master_backend.setting_rkbu.rekening_belanja.index',
            compact('rekening_belanjas', 'aktivitas', 'sub_kategori_rekenings'),
            [
                'title' => 'Halaman Rekening Belanja',
                'title2' => 'Rekening Belanja',
                'routePrefix' => 'rekening_belanjas',
            ],
        );
    }

    public function create()
    {
        $aktivitas = Aktivitas::all();

        return view('master_backend.setting_rkbu.rekening_belanja.create', compact('aktivitas'), [
            'title' => 'Halaman Rekening Belanja',
            'title2' => 'Rekening Belanja',
            'routePrefix' => 'rekening_belanjas',
        ]);
    }

    public function store(Request $request)
    {
        RekeningBelanja::create($this->validatedData($request));

        return redirect()
            ->route('rekening_belanjas.index')
            ->with('success', 'Data Berhasil di Tambahkan !!');
    }

    /**
     * Display the specified resource.
     */
    public function show(RekeningBelanja $rekeningBelanja)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RekeningBelanja $rekeningBelanja)
    {
        $aktivitas = Aktivitas::all();
        $rekeningBelanja->load(['aktivitas', 'sub_kategori_rekening']);

        return view(
            'master_backend.setting_rkbu.rekening_belanja.edit',
            compact('aktivitas', 'rekeningBelanja'),
            [
                'title' => 'Halaman Rekening Belanja',
                'title2' => 'Rekening Belanja',
                'routePrefix' => 'rekening_belanjas',
            ],
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RekeningBelanja $rekeningBelanja)
    {
        $rekeningBelanja->update($this->validatedData($request));

        return redirect()
            ->route('rekening_belanjas.index')
            ->with('success', 'Data Berhasil di Update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RekeningBelanja $rekeningBelanja)
    {
        $rekeningBelanja->delete();

        return redirect()
            ->route('rekening_belanjas.index')
            ->with('success', 'Data Berhasil di Hapus');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'aktivitas_id' => ['required', 'exists:aktivitas,id'],
            'sub_kategori_rekening_id' => ['nullable', 'exists:sub_kategori_rekenings,id'],
            'kode_rekening_belanja' => ['required', 'string', 'max:100'],
            'nama_rekening_belanja' => ['required', 'string'],
        ]);
    }
}
