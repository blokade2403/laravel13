<?php

namespace Database\Seeders;

use App\Models\MasterBackend\SettingUser\Position;
use App\Models\Workflow\Workflow;
use App\Models\Workflow\WorkflowStep;
use App\Models\Workflow\WorkflowVersion;
use Illuminate\Database\Seeder;

class WorkflowSeeder extends Seeder
{
    public function run(): void
    {
        $ksp = Position::where(
            'code',
            'KSP'
        )->firstOrFail();

        $kabag = Position::where(
            'code',
            'KABAG'
        )->firstOrFail();

        $ppk = Position::where(
            'code',
            'PPK'
        )->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | WORKFLOW
        |--------------------------------------------------------------------------
        */

        $workflow = Workflow::create([
            'name' => 'Approval RKBU',
            'code' => 'RKBU_APPROVAL',
            'document_type' => 'RKBU',
            'description' => 'Workflow approval dokumen RKBU',
            'is_active' => true,
        ]);

        /*
        |--------------------------------------------------------------------------
        | VERSION
        |--------------------------------------------------------------------------
        */

        $version = WorkflowVersion::create([
            'workflow_id' => $workflow->id,
            'version_no' => 1,
            'name' => 'RKBU Approval v1',
            'status' => 'PUBLISHED',
            'published_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | STEP 1 - KSP
        |--------------------------------------------------------------------------
        */

        WorkflowStep::create([
            'workflow_id' => $workflow->id,
            'workflow_version_id' => $version->id,

            'name' => 'Validasi KSP',
            'step_order' => 1,

            'approval_type' => 'POSITION',
            'position_id' => $ksp->id,

            'target_scope_type' => 'DOCUMENT_UNIT',

            'allow_skip_same_user' => false,
            'can_delegate' => true,
            'is_required' => true,
        ]);

        /*
        |--------------------------------------------------------------------------
        | STEP 2 - KABAG
        |--------------------------------------------------------------------------
        */

        WorkflowStep::create([
            'workflow_id' => $workflow->id,
            'workflow_version_id' => $version->id,

            'name' => 'Validasi Kabag',
            'step_order' => 2,

            'approval_type' => 'POSITION',
            'position_id' => $kabag->id,

            'target_scope_type' => 'DOCUMENT_UNIT',

            'allow_skip_same_user' => false,
            'can_delegate' => true,
            'is_required' => true,
        ]);

        /*
        |--------------------------------------------------------------------------
        | STEP 3 - PPK
        |--------------------------------------------------------------------------
        */

        WorkflowStep::create([
            'workflow_id' => $workflow->id,
            'workflow_version_id' => $version->id,

            'name' => 'Validasi PPK',
            'step_order' => 3,

            'approval_type' => 'POSITION',
            'position_id' => $ppk->id,

            'target_scope_type' => 'DOCUMENT_UNIT',

            /*
             * Jika PPK dan Kabag adalah user yang sama,
             * step ini bisa otomatis dilewati.
             */
            'allow_skip_same_user' => true,

            'can_delegate' => true,
            'is_required' => true,
        ]);
    }
}
