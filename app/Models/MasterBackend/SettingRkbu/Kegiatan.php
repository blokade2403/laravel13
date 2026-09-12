<?php

namespace App\Models\MasterBackend\SettingRkbu;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'program_id',
        'kode_kegiatan',
        'nama_kegiatan',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function sub_kegiatan()
    {
        return $this->hasMany(SubKegiatan::class, 'kegiatan_id');
    }

    public function subKegiatans()
    {
        return $this->hasMany(SubKegiatan::class, 'kegiatan_id');
    }

    public function sub_kegiatans()
    {
        return $this->hasMany(SubKegiatan::class, 'kegiatan_id');
    }
}
