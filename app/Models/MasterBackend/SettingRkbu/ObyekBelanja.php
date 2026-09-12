<?php

namespace App\Models\MasterBackend\SettingRkbu;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObyekBelanja extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'id';

    protected $fillable = [
        'kode_obyek_belanja',
        'nama_obyek_belanja',
        'jenis_kategori_rkbu_id',
    ];

    public function jenis_kategori_rkbu()
    {
        return $this->belongsTo(JenisKategoriRkbu::class, 'jenis_kategori_rkbu_id');
    }

    public function jenisKategoriRkbu()
    {
        return $this->belongsTo(JenisKategoriRkbu::class, 'jenis_kategori_rkbu_id');
    }

    // Relasi ke KategoriRkbu
    public function kategori_rkbus()
    {
        return $this->hasMany(KategoriRkbu::class, 'obyek_belanja_id');
    }
}
