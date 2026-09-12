<?php

namespace App\Models\MasterBackend\SettingUser;

use App\Models\MasterBackend\SettingRkbu\SubKategoriRkbuResponsible;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OfficialRole extends Model
{
    use HasFactory;

    protected $table = 'official_roles';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $model) {
            if (empty($model->getKey())) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function subKategoriRkbuResponsibles()
    {
        return $this->hasMany(
            SubKategoriRkbuResponsible::class,
            'official_role_id'
        );
    }
}
