<?php

namespace App\Http\Controllers\MasterBackend\SettingUser;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingUser\Position;
use App\Models\MasterBackend\SettingUser\Unit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PositionController extends Controller
{
    public function index()
    {
        return view('master_backend.setting_users.positions.index', [
            'positions' => Position::with('unit')->orderBy('nama_jabatan')->get(),
            'units' => Unit::orderBy('nama_unit')->get(),
            'routePrefix' => 'positions',
            'title' => 'Halaman Jabatan',
            'title2' => 'Jabatan',
        ]);
    }

    public function create()
    {
        return view('master_backend.setting_users.positions.create', [
            'units' => Unit::orderBy('nama_unit')->get(),
            'routePrefix' => 'positions',
            'title' => 'Halaman Jabatan',
            'title2' => 'Jabatan',
        ]);
    }

    public function store(Request $request)
    {
        Position::create($this->validatedData($request));

        return redirect()->route('positions.index')->with('success', 'Jabatan berhasil ditambahkan.');
    }

    public function show(Position $position)
    {
        return view('master_backend.setting_users.positions.show', compact('position'));
    }

    public function edit(Position $position)
    {
        return view('master_backend.setting_users.positions.edit', [
            'position' => $position->load('unit'),
            'units' => Unit::orderBy('nama_unit')->get(),
            'routePrefix' => 'positions',
            'title' => 'Halaman Jabatan',
            'title2' => 'Jabatan',
        ]);
    }

    public function update(Request $request, Position $position)
    {
        $position->update($this->validatedData($request, $position));

        return redirect()->route('positions.index')->with('success', 'Jabatan berhasil diperbarui.');
    }

    public function destroy(Position $position)
    {
        $position->delete();

        return redirect()->route('positions.index')->with('success', 'Jabatan berhasil dihapus.');
    }

    private function validatedData(Request $request, ?Position $position = null): array
    {
        return $request->validate([
            'unit_id' => ['required', 'exists:units,id'],
            'kode_jabatan' => ['nullable', 'string', 'max:100'],
            'nama_jabatan' => [
                'required',
                'string',
                Rule::unique('positions', 'nama_jabatan')
                    ->where(fn ($query) => $query->where('unit_id', $request->input('unit_id')))
                    ->ignore($position?->id),
            ],
            'level_jabatan' => ['nullable', 'string', 'max:50'],
            'jenis_jabatan' => ['nullable', 'string', 'max:50'],
            'is_validator' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]) + [
            'is_validator' => $request->boolean('is_validator'),
            'is_active' => $request->boolean('is_active'),
        ];
    }
}
