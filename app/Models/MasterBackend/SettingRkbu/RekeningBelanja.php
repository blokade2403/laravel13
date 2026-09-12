<?php

namespace App\Models\MasterBackend\SettingRkbu;

use App\Models\MasterBackend\SettingInput\Anggaran;
use App\Models\MasterBackend\UserProfil\User;
use App\Models\Rkbu\Rkbu;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekeningBelanja extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'id';

    protected $fillable =
        [
            'aktivitas_id',
            'sub_kategori_rekening_id',
            'kode_rekening_belanja',
            'nama_rekening_belanja',
        ];

    public function aktivitas()
    {
        return $this->belongsTo(Aktivitas::class, 'aktivitas_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function anggarans()
    {
        return $this->hasMany(Anggaran::class, 'id_kode_rekening_belanja');
    }

    public function sub_kategori_rkbu()
    {
        return $this->belongsTo(SubKategoriRekening::class, 'sub_kategori_rekening_id');
    }

    public function rkbus()
    {
        return $this->hasMany(Rkbu::class, 'id_kode_rekening_belanja', 'id_kode_rekening_belanja');
    }
}
