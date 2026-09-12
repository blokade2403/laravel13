<?php

namespace App\Models\MasterBackend\SettingUser;

use App\Models\MasterBackend\SettingInput\TahunAnggaran;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fase extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'fases';

    protected $primaryKey = 'id';

    protected $fillable = ['kode_fase', 'nama_fase', 'urutan', 'is_active'];

    public function tahunAnggarans()
    {
        return $this->hasMany(TahunAnggaran::class, 'fase_id', 'id');
    }
}
