<?php

namespace App\Models\MasterBackend\SettingRkbu;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisBelanja extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'jenis_belanjas';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['kode_jenis_belanja', 'nama_jenis_belanja'];

    public function jenis_kategori_rkbus(): HasMany
    {
        return $this->hasMany(JenisKategoriRkbu::class, 'jenis_belanja_id', 'id');
    }
}
