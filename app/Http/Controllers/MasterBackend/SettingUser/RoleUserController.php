<?php

namespace App\Http\Controllers\MasterBackend\SettingUser;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingUser\Role;
use App\Models\MasterBackend\UserProfil\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleUserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        $roles = Role::all();

        return view('master_backend.role_user.index', compact('users', 'roles'));
    }

    public function assign(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'roles' => 'required|array',
        ]);

        $user = User::findOrFail($request->user_id);

        DB::table('role_assignments')
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->update([
                'is_active' => false,
                'tanggal_selesai' => now()->toDateString(),
            ]);

        foreach ($request->roles as $roleId) {
            $user->roles()->attach($roleId, [
                'id' => (string) str()->uuid(),
                'tanggal_mulai' => now()->toDateString(),
                'is_active' => true,
            ]);
        }

        return back()->with('success', 'Role berhasil diupdate untuk user');
    }

    public function remove($user_id, $role_id)
    {
        $user = User::findOrFail($user_id);

        $user->roles()->updateExistingPivot($role_id, [
            'is_active' => false,
            'tanggal_selesai' => now()->toDateString(),
        ]);

        return back()->with('success', 'Role berhasil dilepas');
    }
}
