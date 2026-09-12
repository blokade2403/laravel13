<?php

namespace App\Http\Controllers\MasterBackend\SettingUser;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingUser\PositionAssignment;
use App\Models\MasterBackend\SettingUser\PositionDelegation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PositionDelegationController extends Controller
{
    public function index()
    {
        $delegations = PositionDelegation::with([
            'fromAssignment.user',
            'fromAssignment.position',
            'toAssignment.user',
            'toAssignment.position',
        ])
            ->latest('tanggal_mulai')
            ->paginate(20);

        return view(
            'master.setting-user.position-delegations.index',
            compact('delegations')
        );
    }

    public function create()
    {
        $assignments = PositionAssignment::with([
            'user',
            'position',
            'unit',
        ])
            ->active()
            ->get();

        return view(
            'master.setting-user.position-delegations.form',
            compact('assignments') + ['positionDelegation' => null]
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'from_assignment_id' => [
                'required',
                'exists:position_assignments,id',
            ],
            'position_id' => [
                'required',
                'exists:positions,id',
            ],
            'to_assignment_id' => [
                'required',
                'exists:position_assignments,id',
                'different:from_assignment_id',
            ],
            'tanggal_mulai' => [
                'required',
                'date',
            ],
            'tanggal_selesai' => [
                'required',
                'date',
                'after_or_equal:tanggal_mulai',
            ],
            'alasan' => [
                'required',
                'string',
                'max:500',
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
        ]);

        $from = PositionAssignment::findOrFail(
            $validated['from_assignment_id']
        );

        if ($from->position_id !== $validated['position_id']) {
            return back()->withInput()->with('error', 'Posisi delegasi tidak sesuai dengan assignment pemberi.');
        }

        $to = PositionAssignment::findOrFail(
            $validated['to_assignment_id']
        );

        /*
         * Penerima delegasi harus berada
         * pada unit yang sama.
         */
        if ($from->unit_id !== $to->unit_id) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Delegasi harus berada pada unit yang sama.'
                );
        }

        /*
         * Pastikan assignment pemberi
         * dan penerima masih aktif.
         */
        if (! $from->is_active || ! $to->is_active) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Assignment pemberi atau penerima delegasi tidak aktif.'
                );
        }

        /*
         * Jangan sampai assignment yang sama
         * memiliki delegasi aktif yang tumpang tindih.
         */
        $overlap = PositionDelegation::where(
            'from_assignment_id',
            $from->id
        )
            ->where('is_active', true)
            ->where(function ($query) use ($validated) {
                $query
                    ->whereBetween(
                        'tanggal_mulai',
                        [
                            $validated['tanggal_mulai'],
                            $validated['tanggal_selesai'],
                        ]
                    )
                    ->orWhereBetween(
                        'tanggal_selesai',
                        [
                            $validated['tanggal_mulai'],
                            $validated['tanggal_selesai'],
                        ]
                    );
            })
            ->exists();

        if ($overlap) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Sudah terdapat delegasi pada periode tersebut.'
                );
        }

        DB::transaction(function () use (
            $validated
        ) {
            PositionDelegation::create([
                ...$validated,
                'is_active' => true,
            ]);
        });

        return redirect()
            ->route('master.position-delegations.index')
            ->with(
                'success',
                'Delegasi berhasil ditambahkan.'
            );
    }

    public function show(
        PositionDelegation $positionDelegation
    ) {
        $positionDelegation->load([
            'fromAssignment.user',
            'fromAssignment.position',
            'fromAssignment.unit',
            'toAssignment.user',
            'toAssignment.position',
            'toAssignment.unit',
        ]);

        return redirect()->route('master.position-delegations.index');
    }

    public function edit(
        PositionDelegation $positionDelegation
    ) {
        $assignments = PositionAssignment::with([
            'user',
            'position',
            'unit',
        ])
            ->active()
            ->get();

        return view(
            'master.setting-user.position-delegations.form',
            compact(
                'positionDelegation',
                'assignments'
            )
        );
    }

    public function update(
        Request $request,
        PositionDelegation $positionDelegation
    ) {
        $validated = $request->validate([
            'tanggal_mulai' => [
                'required',
                'date',
            ],
            'tanggal_selesai' => [
                'required',
                'date',
                'after_or_equal:tanggal_mulai',
            ],
            'alasan' => [
                'required',
                'string',
                'max:500',
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
        ]);

        $positionDelegation->update(
            $validated
        );

        return redirect()
            ->route('master.position-delegations.index')
            ->with(
                'success',
                'Delegasi berhasil diperbarui.'
            );
    }

    public function destroy(
        PositionDelegation $positionDelegation
    ) {
        $positionDelegation->update([
            'is_active' => false,
            'tanggal_selesai' => now()->toDateString(),
        ]);

        return redirect()
            ->route('master.position-delegations.index')
            ->with(
                'success',
                'Delegasi berhasil dinonaktifkan.'
            );
    }
}
