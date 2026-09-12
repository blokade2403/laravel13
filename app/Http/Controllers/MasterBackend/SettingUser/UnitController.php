<?php

namespace App\Http\Controllers\MasterBackend\SettingUser;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingUser\Unit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::with('parent')->orderBy('nama_unit')->get();

        return view('master_backend.setting_users.unit.index', [
            'units' => $units,
            'title' => 'Halaman Unit',
            'title2' => 'Unit',
            'routePrefix' => 'units',
        ]);
    }

    public function create()
    {
        return view('master_backend.setting_users.unit.create', [
            'title' => 'Halaman Unit',
            'title2' => 'Unit',
            'routePrefix' => 'units',
        ]);
    }

    public function store(Request $request)
    {
        Unit::create($this->validatedData($request));

        return redirect()->route('units.index')->with('success', 'KSP berhasil ditambahkan.');
    }

    public function show(Unit $unit)
    {
        //
    }

    public function edit(Unit $unit)
    {
        return view('master_backend.setting_users.unit.edit', [
            'units' => $unit,
            'title' => 'Halaman Unit',
            'title2' => 'Unit',
            'routePrefix' => 'units',
        ]);
    }

    public function update(Request $request, Unit $unit)
    {
        $unit->update($this->validatedData($request, $unit));

        return redirect()->route('units.index')->with('success', 'Data Unit berhasil Di Update.');
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();

        return redirect()->route('units.index')->with('success', 'Data berhasil dihapus');
    }

    private function validatedData(Request $request, ?Unit $unit = null): array
    {
        return $request->validate([
            'parent_id' => ['nullable', 'exists:units,id', Rule::notIn([$unit?->id])],
            'kode_unit' => ['required', 'string', 'max:50', Rule::unique('units', 'kode_unit')->ignore($unit?->id)],
            'nama_unit' => ['required', 'string'],
            'jenis_unit' => ['nullable', 'string', 'max:50'],
            'is_active' => ['nullable', 'boolean'],
        ]) + ['is_active' => $request->boolean('is_active')];
    }
}
