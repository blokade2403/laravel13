<?php

namespace App\Http\Controllers\MasterBackend\SettingRkbu;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingRkbu\JenisKategoriRkbu;
use App\Models\MasterBackend\SettingRkbu\ObyekBelanja;
use Illuminate\Http\Request;

class ObyekBelanjaController extends Controller
{
    public function index()
    {
        $obyek_belanjas = ObyekBelanja::with('jenis_kategori_rkbu')->get();
        $jenis_kategori_rkbus = JenisKategoriRkbu::all();

        return view(
            'master_backend.setting_rkbu.obyek_belanja.index',
            compact('obyek_belanjas', 'jenis_kategori_rkbus'),
            [
                'title' => 'Halaman Obyek Belanja',
                'title2' => 'Obyek Belanja',
                'routePrefix' => 'obyek_belanjas',
            ],
        );
    }

    public function create()
    {
        $jenis_kategori_rkbus = JenisKategoriRkbu::all();

        return view(
            'master_backend.setting_rkbu.obyek_belanja.create',
            compact('jenis_kategori_rkbus'),
            [
                'title' => 'Halaman Obyek Belanja',
                'title2' => 'Obyek Belanja',
                'routePrefix' => 'obyek_belanjas',
            ],
        );
    }

    public function store(Request $request)
    {
        ObyekBelanja::create($this->validatedData($request));

        return redirect()
            ->route('obyek_belanjas.index')
            ->with('success', 'Data Berhasil di Tambahkan !!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ObyekBelanja $obyekBelanja)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ObyekBelanja $obyekBelanja)
    {
        $jenis_kategori_rkbus = JenisKategoriRkbu::all();
        $obyekBelanja->load('jenis_kategori_rkbu');

        return view(
            'master_backend.setting_rkbu.obyek_belanja.edit',
            compact('jenis_kategori_rkbus', 'obyekBelanja'),
            [
                'title' => 'Halaman Obyek Belanja',
                'title2' => 'Obyek Belanja',
                'routePrefix' => 'obyek_belanjas',
            ],
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ObyekBelanja $obyekBelanja)
    {
        $obyekBelanja->update($this->validatedData($request));

        return redirect()
            ->route('obyek_belanjas.index')
            ->with('success', 'Data Berhasil di Update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ObyekBelanja $obyekBelanja)
    {
        $obyekBelanja->delete();

        return redirect()->route('obyek_belanjas.index')->with('success', 'Data Berhasil di Hapus');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'jenis_kategori_rkbu_id' => ['required', 'exists:jenis_kategori_rkbus,id'],
            'kode_obyek_belanja' => ['required', 'string', 'max:100'],
            'nama_obyek_belanja' => ['required', 'string'],
        ]);
    }
}
