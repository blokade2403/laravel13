<?php

namespace App\Models\MasterBackend\SettingRkbu;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory, HasUuids;

    public function kegiatans()
    {
        return $this->hasMany(Kegiatan::class, 'program_id');
    }

    protected $primaryKey = 'id';

    protected $fillable = [
        'kode_program',
        'nama_program',
    ];
}
