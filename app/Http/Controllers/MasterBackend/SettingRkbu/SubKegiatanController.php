<?php

namespace App\Http\Controllers\MasterBackend\SettingRkbu;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingRkbu\Kegiatan;
use App\Models\MasterBackend\SettingRkbu\SubKegiatan;
use App\Models\MasterBackend\SettingRkbu\SumberDana;
use Illuminate\Http\Request;

class SubKegiatanController extends Controller
{
    public function index()
    {
        $kegiatans = Kegiatan::all();
        $sumber_danas = SumberDana::all();
        $sub_kegiatans = SubKegiatan::with(['kegiatan', 'sumber_dana'])->get();

        return view('master_backend.setting_rkbu.sub_kegiatan.index', compact('sub_kegiatans', 'kegiatans', 'sumber_danas'), [
            'title' => 'Halaman Sub Kegiatan',
            'title2' => 'Sub Kegiatan',
            'routePrefix' => 'sub_kegiatans',
        ]);
    }

    public function create()
    {
        return view('master_backend.setting_rkbu.sub_kegiatan.create', [
            'kegiatans' => Kegiatan::all(),
            'sumber_danas' => SumberDana::all(),
            'title' => 'Halaman Sub Kegiatan',
            'title2' => 'Sub Kegiatan',
            'routePrefix' => 'sub_kegiatans',
        ]);
    }

    public function store(Request $request)
    {
        SubKegiatan::create($this->validatedData($request));

        return redirect()->route('sub_kegiatans.index')->with('success', 'Sub Kegiatan berhasil ditambahkan.');
    }

    public function show(SubKegiatan $subKegiatan)
    {
        //
    }

    public function edit(SubKegiatan $subKegiatan)
    {
        return view('master_backend.setting_rkbu.sub_kegiatan.edit', [
            'sub_kegiatans' => $subKegiatan->load(['kegiatan', 'sumber_dana']),
            'kegiatans' => Kegiatan::all(),
            'sumber_danas' => SumberDana::all(),
            'title' => 'Halaman Sub Kegiatan',
            'title2' => 'Sub Kegiatan',
            'routePrefix' => 'sub_kegiatans',
        ]);
    }

    public function update(Request $request, SubKegiatan $subKegiatan)
    {
        $subKegiatan->update($this->validatedData($request));

        return redirect()->route('sub_kegiatans.index')->with('success', 'Sub Kegiatan berhasil diperbarui.');
    }

    public function destroy(SubKegiatan $subKegiatan)
    {
        $subKegiatan->delete();

        return redirect()->route('sub_kegiatans.index')->with('success', 'Sub Kegiatan berhasil dihapus.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'kegiatan_id' => ['required', 'exists:kegiatans,id'],
            'sumber_dana_id' => ['nullable', 'exists:sumber_danas,id'],
            'kode_sub_kegiatan' => ['required', 'string', 'max:100'],
            'nama_sub_kegiatan' => ['required', 'string'],
            'tujuan_sub_kegiatan' => ['nullable', 'string'],
            'indikator_sub_kegiatan' => ['nullable', 'string'],
        ]);
    }
}
