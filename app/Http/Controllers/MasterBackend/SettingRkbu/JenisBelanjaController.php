<?php

namespace App\Http\Controllers\MasterBackend\SettingRkbu;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingRkbu\JenisBelanja;
use Illuminate\Http\Request;

class JenisBelanjaController extends Controller
{
    public function index()
    {
        $jenis_belanjas = JenisBelanja::all();

        return view('master_backend.setting_rkbu.jenis_belanja.index', compact('jenis_belanjas'), [
            'title' => 'Halaman Jenis Belanja',
            'title2' => 'Jenis Belanja',
            'routePrefix' => 'jenis_belanjas',
        ]);
    }

    public function create()
    {

        return view('master_backend.setting_rkbu.jenis_belanja.create', [
            'title' => 'Halaman Jenis Belanja',
            'title2' => 'Jenis Belanja',
            'routePrefix' => 'jenis_belanjas',
        ]);
    }

    public function store(Request $request)
    {
        JenisBelanja::create($this->validatedData($request));

        return redirect()->route('jenis_belanjas.index')->with('success', 'Jenis Belanja berhasil ditambahkan.');
    }

    public function show(JenisBelanja $jenisBelanja)
    {
        //
    }

    public function edit(JenisBelanja $jenisBelanja)
    {
        return view('master_backend.setting_rkbu.jenis_belanja.edit', compact('jenisBelanja'), [
            'title' => 'Halaman Jenis Belanja',
            'title2' => 'Jenis Belanja',
            'routePrefix' => 'jenis_belanjas',
        ]);
    }

    public function update(Request $request, JenisBelanja $jenisBelanja)
    {
        $jenisBelanja->update($this->validatedData($request));

        return redirect()->route('jenis_belanjas.index')->with('success', 'Jenis Belanja berhasil diperbarui.');
    }

    public function destroy(JenisBelanja $jenisBelanja)
    {
        $jenisBelanja->delete();

        return redirect()->route('jenis_belanjas.index')
            ->with('success', 'Jenis Belanja deleted successfully.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'kode_jenis_belanja' => ['required'],
            'nama_jenis_belanja' => ['required'],
        ], [
            'kode_jenis_belanja.required' => 'Kode Jenis Belanja wajib diisi.',
            'nama_jenis_belanja.required' => 'Nama Jenis Belanja wajib diisi.',
        ]);
    }
}
