<?php

namespace App\Services\Organization;

use App\Models\MasterBackend\PositionAssignment;
use App\Models\MasterBackend\PositionHierarchy;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class PositionResolverService
{
    /**
     * Assignment aktif milik user pada tanggal tertentu.
     */
    public function resolveUserAssignment(
        string $userId,
        ?string $unitId = null,
        $date = null,
        bool $primaryOnly = false
    ): ?PositionAssignment {
        $date = $date ?? now()->toDateString();

        return PositionAssignment::query()
            ->with(['user', 'position', 'unit'])
            ->where('user_id', $userId)
            ->where('is_active', true)
            ->when($unitId, fn ($q) => $q->where('unit_id', $unitId))
            ->when($primaryOnly, fn ($q) => $q->where('is_primary', true))
            ->whereDate('tanggal_mulai', '<=', $date)
            ->where(function ($q) use ($date) {
                $q->whereNull('tanggal_selesai')
                    ->orWhereDate('tanggal_selesai', '>=', $date);
            })
            ->orderByDesc('is_primary')
            ->orderByDesc('tanggal_mulai')
            ->first();
    }

    /**
     * Assignment aktif pada unit tertentu untuk position tertentu.
     *
     * Dipakai untuk mencari KSP/Kabag/Kabid/Direktur yang benar
     * berdasarkan cabang organisasi RS.
     */
    public function resolveAssignmentForPosition(
        string $positionId,
        string $unitId,
        $date = null
    ): ?PositionAssignment {
        $date = $date ?? now()->toDateString();

        return PositionAssignment::query()
            ->with(['user', 'position', 'unit'])
            ->where('position_id', $positionId)
            ->where('unit_id', $unitId)
            ->where('is_active', true)
            ->whereDate('tanggal_mulai', '<=', $date)
            ->where(function ($q) use ($date) {
                $q->whereNull('tanggal_selesai')
                    ->orWhereDate('tanggal_selesai', '>=', $date);
            })
            ->orderByDesc('is_primary')
            ->orderByDesc('tanggal_mulai')
            ->first();
    }

    /**
     * Ambil parent position dari position_hierarchies.
     *
     * Contoh:
     * Staff -> KSP
     * KSP -> Kabid
     * Kabid -> Direktur
     */
    public function resolveParentPosition(
        PositionAssignment $assignment,
        $date = null
    ): ?PositionHierarchy {
        $date = $date ?? now()->toDateString();

        return PositionHierarchy::query()
            ->with(['position', 'parentPosition'])
            ->where('position_id', $assignment->position_id)
            ->where('is_active', true)
            ->whereDate('tanggal_mulai', '<=', $date)
            ->where(function ($q) use ($date) {
                $q->whereNull('tanggal_selesai')
                    ->orWhereDate('tanggal_selesai', '>=', $date);
            })
            ->orderBy('tanggal_mulai')
            ->first();
    }

    /**
     * Naik satu level organisasi.
     *
     * Prioritas:
     * 1. Cari parent position dari position_hierarchies.
     * 2. Cari pejabat dengan parent position pada unit yang sama.
     * 3. Jika tidak ditemukan dan unit memiliki parent_id,
     *    naik ke parent unit lalu cari parent position di sana.
     */
    public function resolveNextSupervisor(
        PositionAssignment $assignment,
        $date = null
    ): ?PositionAssignment {
        $date = $date ?? now()->toDateString();

        $hierarchy = $this->resolveParentPosition($assignment, $date);

        if (!$hierarchy) {
            return null;
        }

        $unitId = $assignment->unit_id;

        // Untuk KSP/Ka Instalasi, Kabag/Kabid biasanya berada di parent unit.
        $candidate = $this->resolveAssignmentForPosition(
            $hierarchy->parent_position_id,
            $unitId,
            $date
        );

        if ($candidate) {
            return $candidate;
        }

        $unit = $assignment->unit;

        if (!$unit || !$unit->parent_id) {
            return null;
        }

        return $this->resolveAssignmentForPosition(
            $hierarchy->parent_position_id,
            $unit->parent_id,
            $date
        );
    }

    /**
     * Membentuk rantai hirarki dari assignment sumber.
     *
     * Contoh:
     * Staff Instalasi A
     * -> KSP Instalasi A
     * -> Kabid Pelayanan
     * -> Direktur
     */
    public function resolveHierarchyChain(
        PositionAssignment $sourceAssignment,
        int $maxDepth = 10,
        $date = null
    ): Collection {
        $date = $date ?? now()->toDateString();

        $chain = collect([$sourceAssignment]);
        $current = $sourceAssignment;
        $visited = [$sourceAssignment->id => true];

        for ($depth = 1; $depth <= $maxDepth; $depth++) {
            $next = $this->resolveNextSupervisor($current, $date);

            if (!$next) {
                break;
            }

            if (isset($visited[$next->id])) {
                throw new RuntimeException(
                    'Circular hierarchy terdeteksi pada position assignment.'
                );
            }

            $visited[$next->id] = true;
            $chain->push($next);
            $current = $next;
        }

        return $chain;
    }

    /**
     * Ambil assignment pejabat pada level tertentu.
     * depth=1 berarti atasan langsung.
     */
    public function resolveHierarchyAssignment(
        PositionAssignment $sourceAssignment,
        int $depth = 1,
        $date = null
    ): ?PositionAssignment {
        return $this->resolveHierarchyChain(
            $sourceAssignment,
            max($depth, 1),
            $date
        )->get($depth);
    }

    /**
     * Validasi bahwa assignment benar-benar aktif pada tanggal tertentu.
     */
    public function isActiveAssignment(
        PositionAssignment $assignment,
        $date = null
    ): bool {
        $date = $date ?? now()->toDateString();

        return $assignment->is_active
            && $assignment->tanggal_mulai <= $date
            && (
                !$assignment->tanggal_selesai
                || $assignment->tanggal_selesai >= $date
            );
    }
}
