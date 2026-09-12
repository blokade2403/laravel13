<?php

namespace App\Models\MasterBackend\SettingBelanja;

use App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spj extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'id_spj';

    public function usulanBarang()
    {
        return $this->belongsTo(UsulanBarang::class, 'id_usulan_barang');
    }

    public function subKategoriRkbu()
    {
        return $this->belongsTo(SubKategoriRkbu::class, 'id_sub_kategori_rkbu', 'id_sub_kategori_rkbu');
    }

    public function spjDetail()
    {
        return $this->hasMany(SpjDetail::class, 'id_spj', 'id_spj');
    }

    public function masterSpj()
    {
        return $this->belongsTo(MasterSpj::class, 'id_spj');
    }
}
