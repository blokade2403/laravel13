<?php

namespace App\Models\MasterBackend\SettingInput;

use App\Models\MasterBackend\SettingRkbu\JenisKategoriRkbu;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Komponen extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'komponens';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'jenis_kategori_rkbu_id',
        'kode_barang',
        'kode_komponen',
        'nama_barang',
        'satuan',
        'spek',
        'harga_barang',
        'is_active',
    ];

    public function jenis_kategori_rkbu()
    {
        return $this->belongsTo(JenisKategoriRkbu::class, 'jenis_kategori_rkbu_id', 'id');
    }
}
