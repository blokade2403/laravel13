<?php

namespace App\Http\Controllers\MasterBackend\SettingRkbu;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingRkbu\KategoriRkbu;
use App\Models\MasterBackend\SettingRkbu\RekeningBelanja;
use App\Models\MasterBackend\SettingRkbu\SubKategoriRekening;
use App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu;
use Illuminate\Http\Request;

class SubKategoriRkbuController extends Controller
{
    public function index()
    {
        return view('master_backend.setting_rkbu.sub_kategori_rkbu.index', [
            'sub_kategori_rkbus' => SubKategoriRkbu::with([
                'kategori_rkbu',
                'rekening_belanja',
                'sub_kategori_rekening',
            ])->get(),
            'kategori_rkbus' => KategoriRkbu::all(),
            'sub_kategori_rekenings' => SubKategoriRekening::all(),
            'rekening_belanjas' => RekeningBelanja::all(),
            'title' => 'Halaman Sub Kategori RKBU',
            'title2' => 'Kategori Sub RKBU',
            'routePrefix' => 'sub_kategori_rkbus',
        ]);
    }

    public function create()
    {
        return view('master_backend.setting_rkbu.sub_kategori_rkbu.create', $this->formData());
    }

    public function store(Request $request)
    {
        SubKategoriRkbu::create($this->validatedData($request));

        return redirect()->route('sub_kategori_rkbus.index')->with('success', 'Sub Kategori RKBU berhasil ditambahkan.');
    }

    public function show(SubKategoriRkbu $subKategoriRkbu)
    {
        //
    }

    public function edit(SubKategoriRkbu $subKategoriRkbu)
    {
        return view('master_backend.setting_rkbu.sub_kategori_rkbu.edit', array_merge(
            ['sub_kategori_rkbus' => $subKategoriRkbu->load(['kategori_rkbu', 'rekening_belanja', 'sub_kategori_rekening'])],
            $this->formData(),
        ));
    }

    public function update(Request $request, SubKategoriRkbu $subKategoriRkbu)
    {
        $subKategoriRkbu->update($this->validatedData($request));

        return redirect()->route('sub_kategori_rkbus.index')->with('success', 'Sub Kategori RKBU berhasil diperbarui.');
    }

    public function destroy(SubKategoriRkbu $subKategoriRkbu)
    {
        $subKategoriRkbu->delete();

        return redirect()->route('sub_kategori_rkbus.index')->with('success', 'Sub Kategori RKBU berhasil dihapus.');
    }

    private function formData(): array
    {
        return [
            'kategori_rkbus' => KategoriRkbu::all(),
            'sub_kategori_rekenings' => SubKategoriRekening::all(),
            'rekening_belanjas' => RekeningBelanja::all(),
            'title' => 'Halaman Sub Kategori RKBU',
            'title2' => 'Kategori Sub RKBU',
            'routePrefix' => 'sub_kategori_rkbus',
        ];
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'kategori_rkbu_id' => ['required', 'exists:kategori_rkbus,id'],
            'sub_kategori_rekening_id' => ['nullable', 'exists:sub_kategori_rekenings,id'],
            'rekening_belanja_id' => ['nullable', 'exists:rekening_belanjas,id'],
            'kode_sub_kategori_rkbu' => ['required', 'string', 'max:100'],
            'nama_sub_kategori_rkbu' => ['required', 'string'],
            'status' => ['nullable', 'string', 'max:20'],
        ]);
    }
}
