<?php

namespace App\Models\Rkbu;

use App\Models\MasterBackend\SettingInput\TahunAnggaran;
use App\Models\MasterBackend\SettingRkbu\JenisBelanja;
use App\Models\MasterBackend\SettingRkbu\JenisKategoriRkbu;
use App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu;
use App\Models\MasterBackend\SettingRkbu\SubKegiatan;
use App\Models\MasterBackend\SettingRkbu\SumberDana;
use App\Models\MasterBackend\SettingUser\Unit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Rkbu extends Model
{
    use HasFactory;

    protected $table = 'rkbus';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'nomor_rkbu',
        'tahun_anggaran_id',
        'unit_id',
        'sumber_dana_id',
        'jenis_belanja_id',
        'jenis_kategori_rkbu_id',
        'sub_kategori_rkbu_id',
        'sub_kegiatan_id',
        'created_by',
        'status',
        'version',
        'submitted_at',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'approved_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $model) {
            if (empty($model->getKey())) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    // Relationships
    public function tahun_anggaran()
    {
        return $this->belongsTo(TahunAnggaran::class, 'tahun_anggaran_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function sumber_dana()
    {
        return $this->belongsTo(SumberDana::class, 'sumber_dana_id');
    }

    public function jenis_belanja()
    {
        return $this->belongsTo(JenisBelanja::class, 'jenis_belanja_id');
    }

    public function jenis_kategori_rkbu()
    {
        return $this->belongsTo(JenisKategoriRkbu::class, 'jenis_kategori_rkbu_id');
    }

    public function sub_kategori_rkbu()
    {
        return $this->belongsTo(SubKategoriRkbu::class, 'sub_kategori_rkbu_id');
    }

    public function sub_kegiatan()
    {
        return $this->belongsTo(SubKegiatan::class, 'sub_kegiatan_id');
    }
}
