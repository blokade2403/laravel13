<?php

namespace App\Http\Controllers\MasterBackend\SettingUser;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingUser\Position;
use App\Models\MasterBackend\SettingUser\PositionHierarchy;
use App\Models\MasterBackend\SettingUser\Unit;
use Illuminate\Http\Request;

class PositionHierarchyController extends Controller
{
    public function index(Request $request)
    {
        $query = PositionHierarchy::with([
            'position',
            'parentPosition',
            'unit',
        ]);

        if ($request->filled('unit_id')) {
            $query->where(
                'unit_id',
                $request->unit_id
            );
        }

        $hierarchies = $query
            ->latest()
            ->paginate(20);

        return view(
            'master.setting-user.position-hierarchies.index',
            compact('hierarchies')
        );
    }

    public function create()
    {
        $positions = Position::where(
            'is_active',
            true
        )
            ->orderBy('nama_jabatan')
            ->get();

        $units = Unit::where('is_active', true)->orderBy('nama_unit')->get();

        return view(
            'master.setting-user.position-hierarchies.form',
            compact('positions', 'units') + ['positionHierarchy' => null]
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'unit_id' => [
                'required',
                'exists:units,id',
            ],
            'position_id' => [
                'required',
                'exists:positions,id',
            ],
            'parent_position_id' => [
                'required',
                'exists:positions,id',
                'different:position_id',
            ],
            'jenis_hubungan' => [
                'required',
                'string',
                'max:50',
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
        ]);

        /*
         * Cegah duplikasi hirarki aktif.
         */
        $exists = PositionHierarchy::where(
            'unit_id',
            $validated['unit_id']
        )
            ->where(
                'position_id',
                $validated['position_id']
            )
            ->where(
                'parent_position_id',
                $validated['parent_position_id']
            )
            ->where('is_active', true)
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Hirarki jabatan tersebut sudah tersedia.'
                );
        }

        PositionHierarchy::create([
            ...$validated,
            'is_active' => true,
        ]);

        return redirect()
            ->route('master.position-hierarchies.index')
            ->with(
                'success',
                'Hirarki jabatan berhasil ditambahkan.'
            );
    }

    public function show(
        PositionHierarchy $positionHierarchy
    ) {
        $positionHierarchy->load([
            'position',
            'parentPosition',
            'unit',
        ]);

        return redirect()->route('master.position-hierarchies.index');
    }

    public function edit(
        PositionHierarchy $positionHierarchy
    ) {
        $positions = Position::where(
            'is_active',
            true
        )
            ->orderBy('nama_jabatan')
            ->get();
        $units = Unit::where('is_active', true)->orderBy('nama_unit')->get();

        return view(
            'master.setting-user.position-hierarchies.form',
            compact(
                'positionHierarchy',
                'positions',
                'units'
            )
        );
    }

    public function update(
        Request $request,
        PositionHierarchy $positionHierarchy
    ) {
        $validated = $request->validate([
            'unit_id' => [
                'required',
                'exists:units,id',
            ],
            'position_id' => [
                'required',
                'exists:positions,id',
            ],
            'parent_position_id' => [
                'required',
                'exists:positions,id',
                'different:position_id',
            ],
            'jenis_hubungan' => [
                'required',
                'string',
                'max:50',
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
        ]);

        $positionHierarchy->update(
            $validated
        );

        return redirect()
            ->route('master.position-hierarchies.index')
            ->with(
                'success',
                'Hirarki jabatan berhasil diperbarui.'
            );
    }

    public function destroy(
        PositionHierarchy $positionHierarchy
    ) {
        $positionHierarchy->update([
            'is_active' => false,
            'tanggal_selesai' => now()->toDateString(),
        ]);

        return redirect()
            ->route('master.position-hierarchies.index')
            ->with(
                'success',
                'Hirarki jabatan berhasil dinonaktifkan.'
            );
    }
}
