<?php

namespace App\Models\MasterBackend\SettingInput;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAnggaran extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'tahun_anggarans';

    protected $fillable = ['id', 'tahun', 'nama_tahun_anggaran', 'status'];

    public $incrementing = false;

    protected $keyType = 'string';
}
