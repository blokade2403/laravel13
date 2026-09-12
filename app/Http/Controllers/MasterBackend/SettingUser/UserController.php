<?php

namespace App\Http\Controllers\MasterBackend\SettingUser;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingUser\Position;
use App\Models\MasterBackend\SettingUser\PositionAssignment;
use App\Models\MasterBackend\SettingUser\Role;
use App\Models\MasterBackend\SettingUser\Unit;
use App\Models\MasterBackend\UserProfil\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with([
            'activePositionAssignments.position',
            'activePositionAssignments.unit',
            'roles',
        ])
            ->latest()
            ->paginate(20);

        return view(
            'master_backend.setting_users.users.index',
            $this->formData($users)
        );
    }

    public function create()
    {
        return redirect()->route('users.index');
    }

    public function store(Request $request)
    {
        $validated = $this->validateUser($request);

        DB::transaction(function () use ($validated): void {

            $user = User::create([
                'nip' => $validated['nip'],
                'nama' => $validated['nama'],
                'username' => $validated['username'],
                'email' => $validated['email'] ?? null,
                'password' => Hash::make($validated['password']),
                'status_user' => $validated['status_user'],
            ]);

            /*
             * Buat assignment jabatan utama
             */
            $this->syncPositionAssignment($user, $validated);

            /*
             * Sinkronisasi role aplikasi
             */
            $this->syncRoles(
                $user,
                $validated['roles'] ?? [],
                $validated['unit_id']
            );
        });

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        return redirect()->route('users.index');
    }

    public function edit(User $user)
    {
        return redirect()->route('users.index');
    }

    public function update(Request $request, User $user)
    {
        $validated = $this->validateUser(
            $request,
            $user
        );

        DB::transaction(function () use ($validated, $user): void {

            $data = [
                'nip' => $validated['nip'],
                'nama' => $validated['nama'],
                'username' => $validated['username'],
                'email' => $validated['email'] ?? null,
                'status_user' => $validated['status_user'],
            ];

            /*
             * Password hanya diubah jika diisi.
             */
            if (! empty($validated['password'])) {
                $data['password'] = Hash::make(
                    $validated['password']
                );
            }

            $user->update($data);

            /*
             * Sinkronisasi assignment jabatan/unit.
             */
            $this->syncPositionAssignment(
                $user,
                $validated
            );

            /*
             * Sinkronisasi role.
             */
            $this->syncRoles(
                $user,
                $validated['roles'] ?? [],
                $validated['unit_id']
            );
        });

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        DB::transaction(function () use ($user) {

            $user->update([
                'status_user' => 'nonaktif',
            ]);

            $this->deactivateAssignmentsAndRoles($user);
        });

        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'User berhasil dinonaktifkan.'
            );
    }

    public function updateStatus(
        Request $request,
        string $id
    ) {
        $validated = $request->validate([
            'status_user' => [
                'required',
                Rule::in([
                    'aktif',
                    'nonaktif',
                ]),
            ],
        ]);

        $user = User::findOrFail($id);

        DB::transaction(function () use (
            $validated,
            $user
        ) {

            $user->update([
                'status_user' => $validated['status_user'],
            ]);

            /*
             * Jika dinonaktifkan,
             * seluruh assignment aktif ikut ditutup.
             */
            if (
                $validated['status_user']
                === 'nonaktif'
            ) {
                $this->deactivateAssignmentsAndRoles(
                    $user
                );
            }
        });

        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'Status user berhasil diperbarui.'
            );
    }

    /**
     * ==========================================================
     * VALIDATION
     * ==========================================================
     */
    private function validateUser(
        Request $request,
        ?User $user = null
    ): array {

        return $request->validate([

            /*
             * USER
             */
            'username' => [
                'required',
                'string',
                'max:100',

                Rule::unique('users', 'username')
                    ->ignore($user?->id),
            ],

            'nip' => [
                'required',
                'string',
                'max:50',

                Rule::unique('users', 'nip')
                    ->ignore($user?->id),
            ],

            'nama' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',

                Rule::unique('users', 'email')
                    ->ignore($user?->id),
            ],

            'password' => [
                $user ? 'nullable' : 'required',
                'string',
                'min:8',
                'confirmed',
            ],

            'status_user' => [
                'required',
                Rule::in([
                    'aktif',
                    'nonaktif',
                ]),
            ],

            /*
             * ==================================================
             * ORGANISASI
             * ==================================================
             */

            /*
             * Posisi/jabatan.
             *
             * Contoh:
             * - Staff
             * - KSP / Ka. Instalasi
             * - Kabag
             * - Kabid
             * - Direktur
             */
            'position_id' => [
                'required',
                'uuid',
                'exists:positions,id',
            ],

            /*
             * Unit organisasi.
             *
             * Contoh:
             * - Rumah Sakit
             * - Bagian Umum
             * - Bidang Pelayanan
             * - Instalasi Rawat Jalan
             */
            'unit_id' => [
                'required',
                'uuid',
                'exists:units,id',
            ],

            /*
             * Role aplikasi.
             */
            'roles' => [
                'nullable',
                'array',
            ],

            'roles.*' => [
                'uuid',
                'exists:roles,id',
            ],
        ]);
    }

    /**
     * ==========================================================
     * FORM DATA
     * ==========================================================
     */
    private function formData($users): array
    {
        return [
            'users' => $users,

            /*
             * Untuk dropdown form.
             */
            'units' => Unit::query()
                ->where('is_active', true)
                ->orderBy('nama_unit')
                ->get(),

            'positions' => Position::query()
                ->where('is_active', true)
                ->orderBy('nama_jabatan')
                ->get(),

            'roles' => Role::query()
                ->where('is_active', true)
                ->orderBy('nama_role')
                ->get(),

            'routePrefix' => 'users',

            'title' => 'Halaman User',

            'title2' => 'User',
        ];
    }

    /**
     * ==========================================================
     * POSITION ASSIGNMENT
     * ==========================================================
     *
     * Prinsip:
     *
     * User
     *   ↓
     * PositionAssignment
     *   ↓
     * Position
     *   ↓
     * Unit
     *
     * Assignment lama tidak langsung diubah.
     * Jika posisi/unit berubah, assignment lama ditutup
     * dan dibuat assignment baru.
     */
    private function syncPositionAssignment(
        User $user,
        array $validated
    ): void {

        $today = now()->toDateString();

        /*
         * Cari primary assignment aktif.
         */
        $current = $user
            ->positionAssignments()
            ->where('is_primary', true)
            ->where('is_active', true)
            ->first();

        /*
         * ======================================================
         * BELUM ADA ASSIGNMENT
         * ======================================================
         */
        if (! $current) {

            PositionAssignment::create([
                'user_id' => $user->id,

                'position_id' => $validated['position_id'],

                'unit_id' => $validated['unit_id'],

                'tanggal_mulai' => $today,

                'assignment_type' => 'DEFINITIF',

                'is_primary' => true,

                'is_active' => true,
            ]);

            return;
        }

        /*
         * ======================================================
         * CEK APAKAH POSISI / UNIT BERUBAH
         * ======================================================
         */
        $positionChanged =
            $current->position_id
            !== $validated['position_id'];

        $unitChanged =
            $current->unit_id
            !== $validated['unit_id'];

        /*
         * Tidak ada perubahan.
         */
        if (
            ! $positionChanged
            && ! $unitChanged
        ) {
            return;
        }

        /*
         * ======================================================
         * TUTUP ASSIGNMENT LAMA
         * ======================================================
         */
        $current->update([
            'is_active' => false,

            /*
             * Assignment lama berakhir kemarin.
             */
            'tanggal_selesai' => now()
                ->subDay()
                ->toDateString(),
        ]);

        /*
         * ======================================================
         * BUAT ASSIGNMENT BARU
         * ======================================================
         */
        PositionAssignment::create([
            'user_id' => $user->id,

            'position_id' => $validated['position_id'],

            'unit_id' => $validated['unit_id'],

            'tanggal_mulai' => $today,

            'assignment_type' => 'DEFINITIF',

            'is_primary' => true,

            'is_active' => true,
        ]);
    }

    /**
     * ==========================================================
     * ROLE
     * ==========================================================
     */
    private function syncRoles(
        User $user,
        array $roleIds,
        string $unitId
    ): void {

        /*
         * Tutup role aktif lama.
         */
        DB::table('role_assignments')
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->update([
                'is_active' => false,

                'tanggal_selesai' => now()
                    ->subDay()
                    ->toDateString(),
            ]);

        /*
         * Buat role baru.
         */
        foreach (array_unique($roleIds) as $roleId) {

            DB::table('role_assignments')->insert([
                'id' => (string) str()->uuid(),

                'user_id' => $user->id,

                'role_id' => $roleId,

                'unit_id' => $unitId,

                'tanggal_mulai' => now()
                    ->toDateString(),

                'is_active' => true,

                'created_at' => now(),

                'updated_at' => now(),
            ]);
        }
    }

    /**
     * ==========================================================
     * DEACTIVATE
     * ==========================================================
     */
    private function deactivateAssignmentsAndRoles(
        User $user
    ): void {

        /*
         * Tutup seluruh assignment aktif.
         */
        $user->positionAssignments()
            ->where('is_active', true)
            ->update([
                'is_active' => false,

                'tanggal_selesai' => now()
                    ->toDateString(),
            ]);

        /*
         * Tutup seluruh role aktif.
         */
        DB::table('role_assignments')
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->update([
                'is_active' => false,

                'tanggal_selesai' => now()
                    ->toDateString(),
            ]);
    }
}
