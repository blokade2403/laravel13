<?php

namespace App\Models\MasterBackend\SettingRkbu;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aktivitas extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'id';

    protected $fillable = [
        'kode_aktivitas',
        'nama_aktivitas',
        'program_id',
        'sub_kegiatan_id',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function sub_kegiatan()
    {
        return $this->belongsTo(SubKegiatan::class, 'sub_kegiatan_id');
    }

    public function rekening_belanja()
    {
        return $this->hasMany(RekeningBelanja::class, 'aktivitas_id');
    }

    public function rekening_belanjas()
    {
        return $this->hasMany(RekeningBelanja::class, 'id_aktivitas', 'id_aktivitas');
    }

    public function kodeRekeningBelanja()
    {
        return $this->hasMany(RekeningBelanja::class, 'id_aktivitas');
    }

    public function kegiatans()
    {
        return $this->belongsTo(Kegiatan::class, 'id_kegiatan');
    }

    public function sub_kegiatans()
    {
        return $this->hasMany(SubKegiatan::class, 'id_aktivitas', 'id');
    }
}
