<?php

namespace App\Services\Workflow;

use App\Models\MasterBackend\PositionAssignment;
use App\Models\Workflow\WorkflowStep;
use App\Services\Organization\PositionResolverService;
use App\Services\Rkbu\SubKategoriRkbuResponsibleService;
use RuntimeException;

class ApprovalTargetResolverService
{
    public function __construct(
        protected PositionResolverService $positionResolver,
        protected SubKategoriRkbuResponsibleService $responsibleResolver
    ) {}

    /**
     * Resolve target approval berdasarkan workflow step.
     *
     * HIERARCHY / POSITION:
     *   menggunakan rantai organisasi RS.
     *
     * OFFICIAL_ROLE:
     *   menggunakan pejabat yang ditunjuk pada Sub Kategori RKBU.
     */
    public function resolve(
        WorkflowStep $step,
        PositionAssignment $sourceAssignment,
        ?string $subKategoriRkbuId = null,
        ?string $unitId = null,
        $date = null
    ): array {
        $date = $date ?? now()->toDateString();

        $type = strtoupper($step->approval_type);

        if (in_array($type, ['HIERARCHY', 'POSITION'], true)) {
            return $this->resolveHierarchy(
                $step,
                $sourceAssignment,
                $date
            );
        }

        if ($type === 'OFFICIAL_ROLE') {
            if (!$subKategoriRkbuId) {
                throw new RuntimeException(
                    'sub_kategori_rkbu_id wajib untuk approval berdasarkan official role.'
                );
            }

            $responsible = $this->responsibleResolver->resolve(
                $subKategoriRkbuId,
                $unitId,
                $date,
                $step->officialRole?->kode ?? 'PPTK'
            );

            return [
                'position_id' => $responsible->positionAssignment->position_id,
                'assignment_id' => $responsible->position_assignment_id,
                'official_role_id' => $responsible->official_role_id,
                'user_id' => $responsible->positionAssignment->user_id,
                'unit_id' => $responsible->positionAssignment->unit_id,
            ];
        }

        throw new RuntimeException(
            "Approval type [{$step->approval_type}] belum didukung."
        );
    }

    protected function resolveHierarchy(
        WorkflowStep $step,
        PositionAssignment $sourceAssignment,
        $date
    ): array {
        $depth = max((int) ($step->hierarchy_depth ?? 1), 1);

        $assignment = $this->positionResolver
            ->resolveHierarchyAssignment(
                $sourceAssignment,
                $depth,
                $date
            );

        if (!$assignment) {
            throw new RuntimeException(
                "Pejabat approval tidak ditemukan untuk step [{$step->kode_step}]. "
                . "Pastikan position_hierarchies, units, dan position_assignments sudah benar."
            );
        }

        if (
            $step->position_id
            && $assignment->position_id !== $step->position_id
        ) {
            throw new RuntimeException(
                "Pejabat yang ditemukan tidak sesuai position_id workflow step [{$step->kode_step}]."
            );
        }

        return [
            'position_id' => $assignment->position_id,
            'assignment_id' => $assignment->id,
            'official_role_id' => $step->official_role_id,
            'user_id' => $assignment->user_id,
            'unit_id' => $assignment->unit_id,
        ];
    }
}
