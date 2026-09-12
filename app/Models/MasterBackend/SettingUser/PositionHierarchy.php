<?php

namespace App\Models\MasterBackend\SettingUser;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PositionHierarchy extends Model
{
    use HasFactory;

    protected $table = 'position_hierarchies';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'unit_id',
        'position_id',
        'parent_position_id',
        'jenis_hubungan',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_active',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (! $model->id) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * Jabatan bawahan
     */
    public function position()
    {
        return $this->belongsTo(
            Position::class,
            'position_id'
        );
    }

    /**
     * Jabatan atasan
     */
    public function parentPosition()
    {
        return $this->belongsTo(
            Position::class,
            'parent_position_id'
        );
    }

    public function unit()
    {
        return $this->belongsTo(
            Unit::class,
            'unit_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query
            ->where('is_active', true)
            ->whereDate('tanggal_mulai', '<=', now())
            ->where(function ($query) {
                $query->whereNull('tanggal_selesai')
                    ->orWhereDate('tanggal_selesai', '>=', now());
            });
    }

    public function scopeForUnit($query, string $unitId)
    {
        return $query->where('unit_id', $unitId);
    }
}
