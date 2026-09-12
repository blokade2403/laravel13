<?php

namespace App\Http\Controllers\MasterBackend\SettingUser;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingUser\Fase;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FaseController extends Controller
{
    public function index()
    {
        $fases = Fase::orderBy('urutan')->get();

        return view('master_backend.setting_users.fase.index', [
            'fases' => $fases,
            'title' => 'Halaman Fase',
            'title2' => 'Fase',
            'routePrefix' => 'fases',
        ]);
    }

    public function create()
    {
        return view('master_backend.setting_users.fase.create', [
            'title' => 'Halaman Fase',
            'title2' => 'Fase',
            'routePrefix' => 'fases',
        ]);
    }

    public function store(Request $request)
    {
        Fase::create($this->validatedData($request));

        return redirect()
            ->route('fases.index')
            ->with('success', 'Data Struktur fase Berhasil di Tambahkan');
    }

    public function show(Fase $fase)
    {
        //
    }

    public function edit(Fase $fase)
    {
        return view('master_backend.setting_users.fase.edit', [
            'fases' => $fase,
            'title' => 'Halaman Fase',
            'title2' => 'Fase',
            'routePrefix' => 'fases',
        ]);
    }

    public function update(Request $request, Fase $fase)
    {
        $fase->update($this->validatedData($request, $fase));

        return redirect()
            ->route('fases.index')
            ->with('success', 'Data Struktur fase Berhasil di Update');
    }

    public function destroy(Fase $fase)
    {
        $fase->delete();

        return redirect()->route('fases.index')->with('success', 'Data fase Berhasil dihapus.');
    }

    private function validatedData(Request $request, ?Fase $fase = null): array
    {
        return $request->validate([
            'kode_fase' => ['required', 'string', 'max:255', Rule::unique('fases', 'kode_fase')->ignore($fase?->id)],
            'nama_fase' => ['required', 'string', 'max:255'],
            'urutan' => ['required', 'integer', 'min:1'],
            'is_active' => ['nullable', 'boolean'],
        ]) + ['is_active' => $request->boolean('is_active')];
    }
}
