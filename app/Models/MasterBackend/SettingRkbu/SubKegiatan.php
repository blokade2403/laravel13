<?php

namespace App\Models\MasterBackend\SettingRkbu;

use App\Models\Rkbu\Rkbu;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class SubKegiatan extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'sub_kegiatans';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'kegiatan_id',
        'kode_sub_kegiatan',
        'nama_sub_kegiatan',
        'sumber_dana_id',
        'tujuan_sub_kegiatan',
        'indikator_sub_kegiatan',
    ];

    public function kegiatan(): BelongsTo
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }

    public function sumberDana(): BelongsTo
    {
        return $this->belongsTo(SumberDana::class, 'sumber_dana_id');
    }

    public function sumber_danas(): BelongsTo
    {
        return $this->sumberDana();
    }

    public function aktivitas(): HasMany
    {
        return $this->hasMany(Aktivitas::class, 'sub_kegiatan_id');
    }

    public function rekeningBelanjas(): HasManyThrough
    {
        return $this->hasManyThrough(
            RekeningBelanja::class,
            Aktivitas::class,
            'sub_kegiatan_id',
            'aktivitas_id',
            'id',
            'id',
        );
    }

    public function rkbus(): HasMany
    {
        return $this->hasMany(Rkbu::class, 'sub_kegiatan_id');
    }
}
