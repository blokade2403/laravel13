<?php

namespace App\Models\MasterBackend\SettingRkbu;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SumberDana extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'id';

    protected $fillable = [
        'kode_sumber_dana',
        'nama_sumber_dana',
        'is_active',
    ];

    // Fungsi relasi untuk sub_kegiatan
    public function sub_kegiatans()
    {
        return $this->belongsToMany(SubKegiatan::class); // Jika relasi Many-to-Many
        // return $this->hasMany(SubKegiatan::class); // Jika relasi One-to-Many
    }
}
