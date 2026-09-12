<?php

namespace App\Http\Controllers\MasterBackend\SettingInput;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingInput\TahunAnggaran;
use App\Models\MasterBackend\SettingInput\TargetSps;
use Illuminate\Http\Request;

class TargetSpsController extends Controller
{
    public function index()
    {
        // Ambil tahun anggaran yang sedang dipakai
        $tahunSession = session('tahun_anggaran');
        $tahun_anggaran = TahunAnggaran::all();
        // Ambil data target sesuai tahun anggaran
        $targets = TargetSps::orderBy('bulan')
            ->when($tahunSession, function ($query) use ($tahunSession) {
                return $query->where('nama_tahun_anggaran', $tahunSession);
            })
            ->get();

        return view('master_backend.setting_input.target_sps.index', compact('targets', 'tahun_anggaran'), [
            'title' => 'Target SPS',
            'title2' => 'Anggaran',
            'routePrefix' => 'target_sps',
        ]);
    }

    public function create() {}

    public function store(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|between:1,12',
            'target' => 'required|numeric|min:0',
            'nama_tahun_anggaran' => 'required|string',
        ]);

        TargetSps::create($request->only(['bulan', 'target', 'nama_tahun_anggaran']));

        return redirect()->route('target_sps.index')->with('success', 'Target berhasil ditambahkan');
    }

    /**
     * Form edit
     */
    public function edit($id) {}

    /**
     * Update target
     */
    public function update(Request $request, TargetSps $targetSp)
    {
        $request->validate([
            'bulan' => 'required|integer|between:1,12',
            'target' => 'required|numeric|min:0',
            'nama_tahun_anggaran' => 'required|string',
        ]);

        $targetSp->update($request->only(['bulan', 'target', 'nama_tahun_anggaran']));

        return redirect()->route('target_sps.index')->with('success', 'Target berhasil diperbarui');
    }

    /**
     * Hapus target
     */
    public function destroy(TargetSps $targetSp)
    {
        $targetSp->delete();

        return redirect()->route('target_sps.index')->with('success', 'Target berhasil dihapus');
    }
}
