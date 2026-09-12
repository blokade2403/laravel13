<?php

namespace App\Models\MasterBackend\SettingRkbu;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubKategoriRekening extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'id';

    protected $fillable =
        [
            'kategori_rekening_id',
            'kode_sub_kategori_rekening',
            'nama_sub_kategori_rekening',
        ];

    public function kategori_rekening()
    {
        return $this->belongsTo(KategoriRekening::class, 'kategori_rekening_id');
    }
}
