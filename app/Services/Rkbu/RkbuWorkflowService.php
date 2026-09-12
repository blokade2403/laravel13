<?php

namespace App\Services\Rkbu;

use App\Models\Rkbu\Rkbu;
use App\Services\Workflow\WorkflowEngineService;
use Illuminate\Support\Facades\DB;

class RkbuWorkflowService
{
    public function __construct(
        protected WorkflowEngineService $workflow
    ) {}

    public function submit(
        Rkbu $rkbu,
        string $userId
    ) {
        return DB::transaction(function () use (
            $rkbu,
            $userId
        ) {

            if ($rkbu->status !== 'DRAFT') {
                throw new \RuntimeException(
                    'RKBU hanya dapat dikirim dari status DRAFT.'
                );
            }

            /*
             * Validasi RKBU sebelum dikirim.
             */
            $this->validateBeforeSubmit($rkbu);

            /*
             * Update status RKBU.
             */
            $rkbu->update([
                'status' => 'SUBMITTED',
            ]);

            /*
             * Jalankan Workflow Engine.
             */
            return $this->workflow->start(
                document: $rkbu,
                documentType: 'RKBU',
                unitId: $rkbu->unit_id,
                createdBy: $userId
            );
        });
    }

    protected function validateBeforeSubmit(
        Rkbu $rkbu
    ): void {

        if (! $rkbu->unit_id) {
            throw new \RuntimeException(
                'Unit RKBU belum ditentukan.'
            );
        }

        if (! $rkbu->created_by) {
            throw new \RuntimeException(
                'Pembuat RKBU belum ditentukan.'
            );
        }

        /*
         * Tambahkan validasi bisnis RKBU
         * di sini.
         */
    }
}
