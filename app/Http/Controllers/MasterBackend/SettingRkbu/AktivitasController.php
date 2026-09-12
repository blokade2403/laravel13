<?php

namespace App\Http\Controllers\MasterBackend\SettingRkbu;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingRkbu\Aktivitas;
use App\Models\MasterBackend\SettingRkbu\Program;
use App\Models\MasterBackend\SettingRkbu\SubKegiatan;
use Illuminate\Http\Request;

class AktivitasController extends Controller
{
    public function index()
    {
        $aktivitas = Aktivitas::with(['program', 'sub_kegiatan'])->get();

        return view('master_backend.setting_rkbu.aktivitas.index', [
            'aktivitas' => $aktivitas,
            'programs' => Program::all(),
            'sub_kegiatans' => SubKegiatan::all(),
            'title' => 'Halaman Aktivitas',
            'title2' => 'Aktivitas',
            'routePrefix' => 'aktivitas',
        ]);
    }

    public function create()
    {
        return view('master_backend.setting_rkbu.aktivitas.create', [
            'programs' => Program::all(),
            'sub_kegiatans' => SubKegiatan::all(),
            'title' => 'Halaman Aktivitas',
            'title2' => 'Aktivitas',
            'routePrefix' => 'aktivitas',
        ]);
    }

    public function store(Request $request)
    {
        Aktivitas::create($this->validatedData($request));

        return redirect()->route('aktivitas.index')->with('success', 'Aktivitas berhasil ditambahkan.');
    }

    public function show(Aktivitas $aktivitas)
    {
        //
    }

    public function edit(Aktivitas $aktivitas)
    {
        return view('master_backend.setting_rkbu.aktivitas.edit', [
            'aktivitas' => $aktivitas->load(['sub_kegiatan', 'program']),
            'programs' => Program::all(),
            'sub_kegiatans' => SubKegiatan::with('kegiatan')->get(),
            'title' => 'Halaman Aktivitas',
            'title2' => 'Aktivitas',
            'routePrefix' => 'aktivitas',
        ]);
    }

    public function update(Request $request, Aktivitas $aktivitas)
    {
        $aktivitas->update($this->validatedData($request));

        return redirect()->route('aktivitas.index')->with('success', 'Aktivitas berhasil diperbarui.');
    }

    public function destroy(Aktivitas $aktivitas)
    {
        $aktivitas->delete();

        return redirect()->route('aktivitas.index')->with('success', 'Aktivitas berhasil dihapus.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'program_id' => ['nullable', 'exists:programs,id'],
            'sub_kegiatan_id' => ['required', 'exists:sub_kegiatans,id'],
            'nama_aktivitas' => ['required', 'string'],
            'kode_aktivitas' => ['required', 'string', 'max:100'],
        ]);
    }
}
