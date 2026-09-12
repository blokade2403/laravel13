<?php

namespace App\Services\Rkbu;

use App\Models\MasterBackend\OfficialRole;
use App\Models\MasterBackend\PositionAssignment;
use App\Models\MasterBackend\SubKategoriRkbuResponsible;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class SubKategoriRkbuResponsibleService
{
    /**
     * Resolve pejabat penanggung jawab aktif untuk Sub Kategori RKBU.
     *
     * Jika unit diberikan, prioritas assignment yang ditetapkan khusus
     * untuk unit tersebut. Record global (unit_id NULL) menjadi fallback.
     */
    public function resolve(
        string $subKategoriRkbuId,
        ?string $unitId = null,
        $date = null,
        ?string $officialRoleCode = 'PPTK'
    ): SubKategoriRkbuResponsible {
        $date = $date ?? now()->toDateString();

        $query = SubKategoriRkbuResponsible::query()
            ->with([
                'positionAssignment.user',
                'positionAssignment.position',
                'positionAssignment.unit',
                'officialRole',
                'unit',
                'subKategoriRkbu',
            ])
            ->where('sub_kategori_rkbu_id', $subKategoriRkbuId)
            ->where('is_active', true)
            ->whereDate('tanggal_mulai', '<=', $date)
            ->where(function ($q) use ($date) {
                $q->whereNull('tanggal_selesai')
                    ->orWhereDate('tanggal_selesai', '>=', $date);
            });

        if ($unitId) {
            $query->where(function ($q) use ($unitId) {
                $q->where('unit_id', $unitId)
                    ->orWhereNull('unit_id');
            });
        }

        if ($officialRoleCode) {
            $query->whereHas('officialRole', function ($q) use ($officialRoleCode) {
                $q->where('kode', $officialRoleCode);
            });
        }

        $records = $query
            ->orderByRaw('CASE WHEN unit_id IS NULL THEN 1 ELSE 0 END')
            ->orderByDesc('tanggal_mulai')
            ->get();

        if ($records->isEmpty()) {
            throw (new ModelNotFoundException)
                ->setModel(SubKategoriRkbuResponsible::class, [$subKategoriRkbuId]);
        }

        if ($records->count() > 1) {
            throw new RuntimeException(
                'Konfigurasi penanggung jawab tidak valid: terdapat lebih dari satu pejabat aktif '
                . 'untuk Sub Kategori RKBU pada periode yang sama.'
            );
        }

        $responsible = $records->first();

        if (!$responsible->positionAssignment) {
            throw new RuntimeException(
                'Position assignment penanggung jawab tidak ditemukan.'
            );
        }

        return $responsible;
    }

    /**
     * Menetapkan satu pejabat sebagai penanggung jawab baru.
     *
     * Record lama ditutup terlebih dahulu agar histori tetap tersimpan.
     */
    public function assign(
        string $subKategoriRkbuId,
        PositionAssignment $assignment,
        ?string $officialRoleId,
        ?string $unitId,
        string $tanggalMulai,
        ?string $nomorSk = null,
        ?string $tanggalSk = null,
        ?string $keterangan = null
    ): SubKategoriRkbuResponsible {
        return DB::transaction(function () use (
            $subKategoriRkbuId,
            $assignment,
            $officialRoleId,
            $unitId,
            $tanggalMulai,
            $nomorSk,
            $tanggalSk,
            $keterangan
        ) {
            $active = SubKategoriRkbuResponsible::query()
                ->where('sub_kategori_rkbu_id', $subKategoriRkbuId)
                ->where('is_active', true)
                ->when(
                    $unitId,
                    fn ($q) => $q->where('unit_id', $unitId),
                    fn ($q) => $q->whereNull('unit_id')
                )
                ->lockForUpdate()
                ->get();

            foreach ($active as $old) {
                $old->update([
                    'is_active' => false,
                    'tanggal_selesai' => date(
                        'Y-m-d',
                        strtotime($tanggalMulai . ' -1 day')
                    ),
                ]);
            }

            return SubKategoriRkbuResponsible::create([
                'sub_kategori_rkbu_id' => $subKategoriRkbuId,
                'position_assignment_id' => $assignment->id,
                'official_role_id' => $officialRoleId,
                'unit_id' => $unitId,
                'tanggal_mulai' => $tanggalMulai,
                'is_active' => true,
                'nomor_sk' => $nomorSk,
                'tanggal_sk' => $tanggalSk,
                'keterangan' => $keterangan,
            ]);
        });
    }
}
