<?php

namespace App\Services\Workflow;

use App\Models\User;
use App\Models\Workflow\Approval;
use App\Models\Workflow\ApprovalLog;
use App\Models\Workflow\WorkflowInstance;
use App\Models\Workflow\WorkflowStep;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class WorkflowEngineService
{
    public function __construct(
        protected WorkflowResolverService $workflowResolver,
        protected PositionResolverService $positionResolver,
    ) {}

    /**
     * Memulai workflow.
     */
    public function start(
        Model $document,
        string $documentType,
        string $unitId,
        string $createdBy
    ): WorkflowInstance {

        return DB::transaction(function () use (
            $document,
            $documentType,
            $unitId,
            $createdBy
        ) {

            $workflow = $this->workflowResolver
                ->resolveWorkflow($documentType);

            $version = $this->workflowResolver
                ->resolvePublishedVersion($workflow);

            $sourceAssignment = $this->positionResolver
                ->resolveUserAssignment(
                    $createdBy,
                    $unitId
                );

            if (! $sourceAssignment) {
                throw new RuntimeException(
                    'User pembuat dokumen tidak memiliki assignment aktif.'
                );
            }

            $instance = WorkflowInstance::create([
                'workflow_id' => $workflow->id,
                'workflow_version_id' => $version->id,
                'source_assignment_id' => $sourceAssignment->id,

                'document_type' => $documentType,
                'document_id' => $document->getKey(),

                'status' => 'IN_PROGRESS',
                'started_at' => now(),
            ]);

            $firstStep = $this->workflowResolver
                ->firstStep($version);

            if (! $firstStep) {
                throw new RuntimeException(
                    'Workflow tidak memiliki step.'
                );
            }

            $this->createApproval(
                $instance,
                $firstStep
            );

            return $instance->fresh([
                'version',
                'sourceAssignment',
                'approvals.workflowStep',
            ]);
        });
    }

    /**
     * Membuat approval untuk step tertentu.
     */
    protected function createApproval(
        WorkflowInstance $instance,
        WorkflowStep $step
    ): ?Approval {

        $assignment = match ($step->approval_type) {

            'HIERARCHY' => $this->positionResolver
                ->resolveHierarchyAssignment(
                    $instance->sourceAssignment,
                    $step->hierarchy_depth ?? 1
                ),

            'POSITION' => $this->resolvePositionStep(
                $instance,
                $step
            ),

            default => null,
        };

        if (! $assignment) {
            throw new RuntimeException(
                "Target approval untuk step {$step->name} tidak ditemukan."
            );
        }

        $approval = Approval::create([
            'workflow_instance_id' => $instance->id,
            'workflow_step_id' => $step->id,

            'target_position_id' => $assignment->position_id,
            'target_assignment_id' => $assignment->id,

            'assigned_user_id' => $assignment->user_id,

            'status' => 'PENDING',
            'assigned_at' => now(),
        ]);

        /*
         * Jika pejabat yang sama sudah melakukan approval
         * pada step sebelumnya dan konfigurasi mengizinkan skip.
         */
        if (
            $step->allow_skip_same_user &&
            $this->hasSameUserApproved(
                $instance,
                $assignment->user_id
            )
        ) {
            $approval->update([
                'status' => 'SKIPPED',
                'approved_at' => now(),
                'catatan' => 'Otomatis dilewati karena user yang sama.',
            ]);

            return $this->moveToNextStep(
                $instance,
                $step
            );
        }

        return $approval;
    }

    protected function resolvePositionStep(
        WorkflowInstance $instance,
        WorkflowStep $step
    ) {
        if (! $step->position_id) {
            throw new RuntimeException(
                "Step {$step->name} tidak memiliki position_id."
            );
        }

        $unitId = $this->resolveTargetUnit(
            $instance,
            $step
        );

        return $this->positionResolver
            ->resolvePositionOrFail(
                $step->position_id,
                $unitId
            );
    }

    protected function resolveTargetUnit(
        WorkflowInstance $instance,
        WorkflowStep $step
    ): string {

        if (
            $step->target_scope_type === 'FIXED_UNIT'
            && $step->target_unit_id
        ) {
            return $step->target_unit_id;
        }

        if (
            $step->target_scope_type === 'DOCUMENT_UNIT'
        ) {
            return $instance
                ->sourceAssignment
                ->unit_id;
        }

        return $instance
            ->sourceAssignment
            ->unit_id;
    }

    /**
     * Approval.
     */
    public function approve(
        string $approvalId,
        User $actor,
        ?string $catatan = null
    ): Approval {

        return DB::transaction(function () use (
            $approvalId,
            $actor,
            $catatan
        ) {

            $approval = Approval::query()
                ->with([
                    'workflowInstance.sourceAssignment',
                    'workflowStep',
                ])
                ->whereKey($approvalId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($approval->status !== 'PENDING') {
                throw new RuntimeException(
                    'Approval sudah tidak berstatus pending.'
                );
            }

            /*
             * Re-resolve assignment.
             * Ini menangani pergantian pejabat.
             */
            $this->refreshPendingAssignee($approval);

            if ($approval->assigned_user_id !== $actor->id) {
                throw new RuntimeException(
                    'User tidak berhak melakukan approval ini.'
                );
            }

            $approval->update([
                'status' => 'APPROVED',
                'catatan' => $catatan,
                'approved_at' => now(),
            ]);

            $this->createApprovalLog(
                $approval,
                $actor,
                'APPROVED',
                $catatan
            );

            return $this->moveToNextStep(
                $approval->workflowInstance,
                $approval->workflowStep
            );
        });
    }

    /**
     * Reject.
     */
    public function reject(
        string $approvalId,
        User $actor,
        string $catatan
    ): Approval {

        return DB::transaction(function () use (
            $approvalId,
            $actor,
            $catatan
        ) {

            $approval = Approval::query()
                ->with([
                    'workflowInstance',
                    'workflowStep',
                ])
                ->whereKey($approvalId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($approval->status !== 'PENDING') {
                throw new RuntimeException(
                    'Approval sudah tidak pending.'
                );
            }

            $this->refreshPendingAssignee($approval);

            if ($approval->assigned_user_id !== $actor->id) {
                throw new RuntimeException(
                    'User tidak berhak melakukan reject.'
                );
            }

            $approval->update([
                'status' => 'REJECTED',
                'catatan' => $catatan,
            ]);

            $this->createApprovalLog(
                $approval,
                $actor,
                'REJECTED',
                $catatan
            );

            $approval->workflowInstance->update([
                'status' => 'REJECTED',
            ]);

            return $approval;
        });
    }

    /**
     * Pindah ke step berikutnya.
     */
    protected function moveToNextStep(
        WorkflowInstance $instance,
        WorkflowStep $currentStep
    ): Approval {

        $nextStep = $this->workflowResolver
            ->nextStep(
                $instance,
                $currentStep
            );

        if (! $nextStep) {

            $instance->update([
                'status' => 'COMPLETED',
                'completed_at' => now(),
            ]);

            return Approval::query()
                ->where('workflow_instance_id', $instance->id)
                ->latest()
                ->first();
        }

        return $this->createApproval(
            $instance,
            $nextStep
        );
    }

    /**
     * Update target user jika pejabat sudah berganti.
     */
    protected function refreshPendingAssignee(
        Approval $approval
    ): void {

        if ($approval->status !== 'PENDING') {
            return;
        }

        if (! $approval->targetPosition) {
            return;
        }

        $assignment = $this->positionResolver
            ->resolveActiveAssignment(
                $approval->targetPosition,
                $approval->workflowInstance
                    ->sourceAssignment
                    ->unit_id
            );

        if (! $assignment) {
            throw new RuntimeException(
                'Pejabat aktif untuk approval tidak ditemukan.'
            );
        }

        if (
            $approval->target_assignment_id !== $assignment->id
            ||
            $approval->assigned_user_id !== $assignment->user_id
        ) {
            $approval->update([
                'target_assignment_id' => $assignment->id,
                'assigned_user_id' => $assignment->user_id,
            ]);
        }
    }

    protected function hasSameUserApproved(
        WorkflowInstance $instance,
        string $userId
    ): bool {

        return $instance->approvals()
            ->where('assigned_user_id', $userId)
            ->where('status', 'APPROVED')
            ->exists();
    }

    protected function createApprovalLog(
        Approval $approval,
        User $actor,
        string $action,
        ?string $catatan
    ): void {

        $assignment = $approval->targetAssignment;

        ApprovalLog::create([
            'approval_id' => $approval->id,

            'user_id' => $actor->id,

            'position_id' => $assignment?->position_id,

            'position_assignment_id' => $assignment?->id,

            'aksi' => $action,

            'catatan' => $catatan,

            'aksi_at' => now(),

            'data_snapshot' => [
                'approval_id' => $approval->id,
                'user_id' => $actor->id,
                'position_id' => $assignment?->position_id,
                'position_assignment_id' => $assignment?->id,
            ],

            'ip_address' => request()->ip(),

            'user_agent' => request()->userAgent(),
        ]);
    }
}
