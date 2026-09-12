<?php

namespace App\Models\MasterBackend\SettingRkbu;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisKategoriRkbu extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'id';

    protected $fillable = ['nama_jenis_kategori_rkbu', 'kode_jenis_kategori_rkbu', 'jenis_belanja_id'];

    // Relasi ke JenisBelanja
    public function jenis_belanja()
    {
        return $this->belongsTo(JenisBelanja::class, 'jenis_belanja_id');
    }

    // Relasi ke ObyekBelanja
    public function obyek_belanjas()
    {
        return $this->hasMany(ObyekBelanja::class, 'jenis_kategori_rkbu_id');
    }
}
