<?php

namespace App\Http\Controllers\MasterBackend\SettingUser;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingUser\Position;
use App\Models\MasterBackend\SettingUser\PositionAssignment;
use App\Models\MasterBackend\SettingUser\Unit;
use App\Models\MasterBackend\UserProfil\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PositionAssignmentController extends Controller
{
    public function index()
    {
        $assignments = PositionAssignment::with([
            'user',
            'position',
            'unit',
        ])
            ->latest('tanggal_mulai')
            ->paginate(20);

        return view(
            'master_backend.setting_users.position_assignment.index',
            compact('assignments'),
            [
                'title' => 'Penempatan Jabatan',
            ]
        );
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        $positions = Position::where('is_active', true)->orderBy('nama_jabatan')->get();
        $units = Unit::where('is_active', true)->orderBy('nama_unit')->get();

        return view(
            'master.setting-user.position-assignments.form',
            compact('users', 'positions', 'units') + ['positionAssignment' => null]
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => [
                'required',
                'exists:users,id',
            ],
            'position_id' => [
                'required',
                'exists:positions,id',
            ],
            'unit_id' => [
                'required',
                'exists:units,id',
            ],
            'assignment_type' => [
                'required',
                Rule::in([
                    'DEFINITIF',
                    'PLT',
                    'PLH',
                    'PENGGANTI',
                ]),
            ],
            'is_primary' => [
                'boolean',
            ],
            'tanggal_mulai' => [
                'required',
                'date',
            ],
            'tanggal_selesai' => [
                'nullable',
                'date',
                'after_or_equal:tanggal_mulai',
            ],
            'nomor_sk' => [
                'nullable',
                'string',
                'max:100',
            ],
            'tanggal_sk' => [
                'nullable',
                'date',
            ],
            'keterangan' => [
                'nullable',
                'string',
            ],
        ]);

        DB::transaction(function () use (
            $request,
            $validated
        ) {

            $isPrimary = $request->boolean(
                'is_primary'
            );

            /*
             * Satu user hanya boleh memiliki
             * satu assignment primary aktif.
             */
            if ($isPrimary) {
                PositionAssignment::where(
                    'user_id',
                    $validated['user_id']
                )
                    ->where('is_active', true)
                    ->update([
                        'is_primary' => false,
                    ]);
            }

            $validated['is_primary'] = $isPrimary;
            $validated['is_active'] = true;

            PositionAssignment::create(
                $validated
            );
        });

        return redirect()
            ->route('master.position-assignments.index')
            ->with(
                'success',
                'Penempatan jabatan berhasil ditambahkan.'
            );
    }

    public function show(
        PositionAssignment $positionAssignment
    ) {
        $positionAssignment->load([
            'user',
            'position',
            'unit',
            'delegations.toAssignment.user',
            'delegatedFrom.fromAssignment.user',
        ]);

        return redirect()->route('master.position-assignments.index');
    }

    public function edit(
        PositionAssignment $positionAssignment
    ) {
        $users = User::orderBy('name')->get();
        $positions = Position::where('is_active', true)->orderBy('nama_jabatan')->get();
        $units = Unit::where('is_active', true)->orderBy('nama_unit')->get();

        return view(
            'master.setting-user.position-assignments.form',
            compact('positionAssignment', 'users', 'positions', 'units')
        );
    }

    public function update(
        Request $request,
        PositionAssignment $positionAssignment
    ) {
        $validated = $request->validate([
            'position_id' => [
                'required',
                'exists:positions,id',
            ],
            'unit_id' => [
                'required',
                'exists:units,id',
            ],
            'assignment_type' => [
                'required',
                Rule::in([
                    'DEFINITIF',
                    'PLT',
                    'PLH',
                    'PENGGANTI',
                ]),
            ],
            'is_primary' => [
                'boolean',
            ],
            'tanggal_mulai' => [
                'required',
                'date',
            ],
            'tanggal_selesai' => [
                'nullable',
                'date',
                'after_or_equal:tanggal_mulai',
            ],
            'nomor_sk' => [
                'nullable',
                'string',
                'max:100',
            ],
            'tanggal_sk' => [
                'nullable',
                'date',
            ],
            'keterangan' => [
                'nullable',
                'string',
            ],
        ]);

        DB::transaction(function () use (
            $request,
            $validated,
            $positionAssignment
        ) {

            if ($request->boolean('is_primary')) {

                PositionAssignment::where(
                    'user_id',
                    $positionAssignment->user_id
                )
                    ->where('id', '!=', $positionAssignment->id)
                    ->where('is_active', true)
                    ->update([
                        'is_primary' => false,
                    ]);
            }

            $validated['is_primary'] =
                $request->boolean('is_primary');

            $positionAssignment->update(
                $validated
            );
        });

        return redirect()
            ->route('master.position-assignments.index')
            ->with(
                'success',
                'Penempatan jabatan berhasil diperbarui.'
            );
    }

    public function destroy(
        PositionAssignment $positionAssignment
    ) {
        $positionAssignment->update([
            'is_active' => false,
            'is_primary' => false,
            'tanggal_selesai' => now()->toDateString(),
        ]);

        return redirect()
            ->route('master.position-assignments.index')
            ->with(
                'success',
                'Penempatan jabatan berhasil dinonaktifkan.'
            );
    }
}
