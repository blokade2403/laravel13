<?php

namespace App\Http\Controllers\MasterBackend\SettingUser;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingUser\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public function index()
    {
        return view('master_backend.setting_users.role.index', [
            'roles' => Role::orderBy('nama_role')->get(),
            'routePrefix' => 'roles',
            'title' => 'Halaman Role',
            'title2' => 'Role',
        ]);
    }

    public function create()
    {
        return view('master_backend.setting_users.role.create');
    }

    public function store(Request $request)
    {
        Role::create($this->validatedData($request));

        return redirect()->route('roles.index')->with('success', 'Role berhasil ditambahkan.');
    }

    public function show(Role $role)
    {
        return view('master_backend.setting_users.role.show', compact('role'));
    }

    public function edit(Role $role)
    {
        return view('master_backend.setting_users.role.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $role->update($this->validatedData($request, $role));

        return redirect()->route('roles.index')->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role berhasil dihapus.');
    }

    private function validatedData(Request $request, ?Role $role = null): array
    {
        return $request->validate([
            'kode_role' => ['required', 'string', 'max:100', Rule::unique('roles', 'kode_role')->ignore($role?->id)],
            'nama_role' => ['required', 'string'],
            'deskripsi' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]) + ['is_active' => $request->boolean('is_active')];
    }
}
