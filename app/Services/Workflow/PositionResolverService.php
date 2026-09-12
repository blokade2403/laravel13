<?php

namespace App\Services\Workflow;

use App\Models\MasterBackend\SettingUser\Position;
use App\Models\MasterBackend\SettingUser\PositionAssignment;
use App\Models\MasterBackend\SettingUser\PositionHierarchy;
use Carbon\Carbon;
use RuntimeException;

class PositionResolverService
{
    public function resolveActiveAssignment(
        Position $position,
        string $unitId,
        ?Carbon $date = null
    ): ?PositionAssignment {
        $date ??= now();

        return PositionAssignment::query()
            ->where('position_id', $position->id)
            ->where('unit_id', $unitId)
            ->where('is_active', true)
            ->whereDate('tanggal_mulai', '<=', $date)
            ->where(function ($query) use ($date) {
                $query
                    ->whereNull('tanggal_selesai')
                    ->orWhereDate('tanggal_selesai', '>=', $date);
            })
            ->orderByDesc('is_primary')
            ->orderByDesc('tanggal_mulai')
            ->first();
    }

    public function resolveUserAssignment(
        string $userId,
        string $unitId,
        ?Carbon $date = null
    ): ?PositionAssignment {
        $date ??= now();

        return PositionAssignment::query()
            ->where('user_id', $userId)
            ->where('unit_id', $unitId)
            ->where('is_active', true)
            ->whereDate('tanggal_mulai', '<=', $date)
            ->where(function ($query) use ($date) {
                $query
                    ->whereNull('tanggal_selesai')
                    ->orWhereDate('tanggal_selesai', '>=', $date);
            })
            ->orderByDesc('is_primary')
            ->orderByDesc('tanggal_mulai')
            ->first();
    }

    public function resolveParentPosition(
        PositionAssignment $assignment,
        ?Carbon $date = null
    ): ?Position {
        $date ??= now();

        $hierarchy = PositionHierarchy::query()
            ->where('child_position_id', $assignment->position_id)
            ->where('unit_id', $assignment->unit_id)
            ->where('is_active', true)
            ->whereDate('tanggal_mulai', '<=', $date)
            ->where(function ($query) use ($date) {
                $query
                    ->whereNull('tanggal_selesai')
                    ->orWhereDate('tanggal_selesai', '>=', $date);
            })
            ->with('parentPosition')
            ->first();

        return $hierarchy?->parentPosition;
    }

    public function resolveHierarchyAssignment(
        PositionAssignment $sourceAssignment,
        int $depth = 1,
        ?Carbon $date = null
    ): ?PositionAssignment {
        $date ??= now();

        $currentPosition = $sourceAssignment->position;

        for ($i = 0; $i < $depth; $i++) {

            $parentPosition = PositionHierarchy::query()
                ->where('child_position_id', $currentPosition->id)
                ->where('unit_id', $sourceAssignment->unit_id)
                ->where('is_active', true)
                ->whereDate('tanggal_mulai', '<=', $date)
                ->where(function ($query) use ($date) {
                    $query
                        ->whereNull('tanggal_selesai')
                        ->orWhereDate('tanggal_selesai', '>=', $date);
                })
                ->with('parentPosition')
                ->first();

            if (! $parentPosition) {
                return null;
            }

            $currentPosition = $parentPosition->parentPosition;
        }

        return $this->resolveActiveAssignment(
            $currentPosition,
            $sourceAssignment->unit_id,
            $date
        );
    }

    public function resolvePositionOrFail(
        string $positionId,
        string $unitId,
        ?Carbon $date = null
    ): PositionAssignment {
        $position = Position::findOrFail($positionId);

        $assignment = $this->resolveActiveAssignment(
            $position,
            $unitId,
            $date
        );

        if (! $assignment) {
            throw new RuntimeException(
                "Tidak ditemukan pejabat aktif untuk jabatan {$position->name}."
            );
        }

        return $assignment;
    }
}
