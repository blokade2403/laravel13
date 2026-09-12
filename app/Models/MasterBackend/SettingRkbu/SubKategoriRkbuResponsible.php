<?php

namespace App\Models\MasterBackend\SettingRkbu;

use App\Models\MasterBackend\SettingUser\OfficialRole;
use App\Models\MasterBackend\SettingUser\PositionAssignment;
use App\Models\MasterBackend\SettingUser\Unit;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubKategoriRkbuResponsible extends Model
{
    use HasUuids;

    protected $table = 'sub_kategori_rkbu_responsibles';

    protected $fillable = [
        'sub_kategori_rkbu_id',
        'position_assignment_id',
        'official_role_id',
        'unit_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_active',
        'nomor_sk',
        'tanggal_sk',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'tanggal_sk' => 'date',
        'is_active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function subKategoriRkbu(): BelongsTo
    {
        return $this->belongsTo(
            SubKategoriRkbu::class,
            'sub_kategori_rkbu_id'
        );
    }

    public function positionAssignment(): BelongsTo
    {
        return $this->belongsTo(
            PositionAssignment::class,
            'position_assignment_id'
        );
    }

    public function officialRole(): BelongsTo
    {
        return $this->belongsTo(
            OfficialRole::class,
            'official_role_id'
        );
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(
            Unit::class,
            'unit_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Scope
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAsOf($query, $date = null)
    {
        $date = $date ?? now()->toDateString();

        return $query
            ->whereDate('tanggal_mulai', '<=', $date)
            ->where(function ($q) use ($date) {
                $q->whereNull('tanggal_selesai')
                    ->orWhereDate('tanggal_selesai', '>=', $date);
            });
    }
}
