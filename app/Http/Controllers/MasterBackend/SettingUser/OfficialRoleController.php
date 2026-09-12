<?php

namespace App\Http\Controllers\MasterBackend\SettingUser;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingUser\OfficialRole;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OfficialRoleController extends Controller
{
    public function index()
    {
        return view('master_backend.setting_users.official_role.index', [
            'officialRoles' => OfficialRole::orderBy('nama')->get(),
            'title' => 'Halaman Official Role',
            'title2' => 'Official Role',
            'routePrefix' => 'official-roles',
        ]);
    }

    public function create()
    {
        return view('master_backend.setting_users.official_role.form', ['officialRole' => null]);
    }

    public function store(Request $request)
    {
        OfficialRole::create($this->validatedData($request));

        return redirect()->route('official-roles.index')->with('success', 'Official role berhasil ditambahkan.');
    }

    public function show(OfficialRole $officialRole)
    {
        return redirect()->route('official-roles.index');
    }

    public function edit(OfficialRole $officialRole)
    {
        return view('master_backend.setting_users.official_role.form', compact('officialRole'));
    }

    public function update(Request $request, OfficialRole $officialRole)
    {
        $officialRole->update($this->validatedData($request, $officialRole));

        return redirect()->route('official-roles.index')->with('success', 'Official role berhasil diperbarui.');
    }

    public function destroy(OfficialRole $officialRole)
    {
        $officialRole->delete();

        return redirect()->route('official-roles.index')->with('success', 'Official role berhasil dihapus.');
    }

    private function validatedData(Request $request, ?OfficialRole $officialRole = null): array
    {
        return $request->validate([
            'kode' => ['required', 'string', 'max:100', Rule::unique('official_roles', 'kode')->ignore($officialRole?->id)],
            'nama' => ['required', 'string'],
            'deskripsi' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]) + ['is_active' => $request->boolean('is_active')];
    }
}
