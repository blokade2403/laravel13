<?php

namespace App\Models\MasterBackend\SettingUser;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'positions';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'unit_id',
        'kode_jabatan',
        'nama_jabatan',
        'level_jabatan',
        'jenis_jabatan',
        'is_validator',
        'is_active',
    ];

    protected function casts(): array
    {
        return ['is_validator' => 'boolean', 'is_active' => 'boolean'];
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    /**
     * Assignment user yang memiliki jabatan ini
     */
    public function assignments()
    {
        return $this->hasMany(
            PositionAssignment::class,
            'position_id'
        );
    }

    /**
     * Jabatan ini sebagai child/bawahan
     *
     * position_id = jabatan ini
     * parent_position_id = atasannya
     */
    public function parentHierarchies()
    {
        return $this->hasMany(
            PositionHierarchy::class,
            'position_id'
        );
    }

    /**
     * Jabatan ini sebagai parent/atasan
     */
    public function childHierarchies()
    {
        return $this->hasMany(
            PositionHierarchy::class,
            'parent_position_id'
        );
    }

    /**
     * Mendapatkan jabatan atasan
     */
    public function parents()
    {
        return $this->belongsToMany(
            self::class,
            'position_hierarchies',
            'position_id',
            'parent_position_id'
        )
            ->withPivot([
                'jenis_hubungan',
                'tanggal_mulai',
                'tanggal_selesai',
                'is_active',
            ]);
    }

    /**
     * Mendapatkan jabatan bawahan
     */
    public function children()
    {
        return $this->belongsToMany(
            self::class,
            'position_hierarchies',
            'parent_position_id',
            'position_id'
        )
            ->withPivot([
                'jenis_hubungan',
                'tanggal_mulai',
                'tanggal_selesai',
                'is_active',
            ]);
    }
}
