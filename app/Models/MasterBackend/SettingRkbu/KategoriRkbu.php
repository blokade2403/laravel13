<?php

namespace App\Models\MasterBackend\SettingRkbu;

use App\Models\Rkbu\Rkbu;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriRkbu extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'id';

    protected $fillable =
        [
            'jenis_kategori_rkbu_id',
            'obyek_belanja_id',
            'kode_kategori_rkbu',
            'nama_kategori_rkbu',
        ];

    public function jenis_kategori_rkbu()
    {
        return $this->belongsTo(
            JenisKategoriRkbu::class,
            'jenis_kategori_rkbu_id',
            'id'
        );
    }

    // Relasi ke ObyekBelanja
    public function obyek_belanja()
    {
        return $this->belongsTo(ObyekBelanja::class, 'obyek_belanja_id');
    }

    public function rkbus()
    {
        return $this->hasMany(Rkbu::class, 'id_kategori_rkbu');
    }

    public function obyekBelanjas()
    {
        return $this->hasMany(ObyekBelanja::class, 'id_kategori_rkbu');
    }
}
