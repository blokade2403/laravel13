<?php

namespace App\Services\Workflow;

use App\Models\Workflow\Workflow;
use App\Models\Workflow\WorkflowInstance;
use App\Models\Workflow\WorkflowStep;
use App\Models\Workflow\WorkflowVersion;

class WorkflowResolverService
{
    public function resolveWorkflow(
        string $documentType
    ): Workflow {
        return Workflow::query()
            ->where('document_type', $documentType)
            ->where('is_active', true)
            ->firstOrFail();
    }

    public function resolvePublishedVersion(
        Workflow $workflow
    ): WorkflowVersion {
        return $workflow->versions()
            ->where('status', 'PUBLISHED')
            ->orderByDesc('version_no')
            ->firstOrFail();
    }

    public function firstStep(
        WorkflowVersion $version
    ): ?WorkflowStep {
        return $version->steps()
            ->where('is_required', true)
            ->orderBy('step_order')
            ->first();
    }

    public function nextStep(
        WorkflowInstance $instance,
        WorkflowStep $currentStep
    ): ?WorkflowStep {
        return $instance->version
            ->steps()
            ->where('is_required', true)
            ->where('step_order', '>', $currentStep->step_order)
            ->orderBy('step_order')
            ->first();
    }
}
