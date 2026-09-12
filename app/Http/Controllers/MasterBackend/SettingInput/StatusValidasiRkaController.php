<?php

namespace App\Http\Controllers\MasterBackend\SettingInput;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingInput\StatusValidasiRka;
use Illuminate\Http\Request;

class StatusValidasiRkaController extends Controller
{
    public function index()
    {
        $status_validasi_rkas = StatusValidasiRka::all();

        return view(
            'master_backend.setting_input.status_validasi_rka.index',
            compact('status_validasi_rkas'),
            [
                'title' => 'Halaman Status Validasi',
                'title2' => 'Status Validasi',
                'routePrefix' => 'status_validasi_rkas',
            ],
        );
    }

    public function create()
    {
        return view('master_backend.setting_input.status_validasi_rka.create', [
            'title' => 'Halaman Status Validasi',
            'title2' => 'Status Validasi',
            'routePrefix' => 'status_validasi_rkas',
        ]);
    }

    public function store(Request $request)
    {
        StatusValidasiRka::create($this->validatedData($request));

        // Set session flash
        return redirect()
            ->route('status_validasi_rkas.index')
            ->with('success', 'Data berhasil ditambahkan.');
    }

    public function show($id)
    {
        $obj = StatusValidasiRka::findOrFail($id);

        return view('backend.setting_input.status_validasi.show', compact('obj'));
    }

    public function edit(StatusValidasiRka $statusValidasiRka)
    {
        return view('master_backend.setting_input.status_validasi_rka.edit', [
            'statusValidasiRka' => $statusValidasiRka,
            'title' => 'Halaman Status Validasi',
            'title2' => 'Status Validasi',
            'routePrefix' => 'status_validasi_rkas',
        ]);
    }

    public function update(Request $request, StatusValidasiRka $statusValidasiRka)
    {
        $statusValidasiRka->update($this->validatedData($request));

        return redirect()
            ->route('status_validasi_rkas.index')
            ->with('success', 'Data berhasil diupdate.');
    }

    public function destroy(StatusValidasiRka $statusValidasiRka)
    {
        $statusValidasiRka->delete();

        return redirect()
            ->route('status_validasi_rkas.index')
            ->with('success', 'Data Berhasil di Hapus');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'nama_status_validasi_rka' => ['required', 'string'],
        ]);
    }
}
