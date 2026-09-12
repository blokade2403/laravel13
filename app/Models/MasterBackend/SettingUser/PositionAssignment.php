<?php

namespace App\Models\MasterBackend\SettingUser;

use App\Models\MasterBackend\SettingRkbu\SubKategoriRkbuResponsible;
use App\Models\MasterBackend\UserProfil\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PositionAssignment extends Model
{
    use HasFactory;

    protected $table = 'position_assignments';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'user_id',
        'position_id',
        'unit_id',
        'assignment_type',
        'is_primary',
        'tanggal_mulai',
        'tanggal_selesai',
        'nomor_sk',
        'tanggal_sk',
        'is_active',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'tanggal_sk' => 'date',
        'is_primary' => 'boolean',
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

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    public function position()
    {
        return $this->belongsTo(
            Position::class,
            'position_id'
        );
    }

    public function unit()
    {
        return $this->belongsTo(
            Unit::class,
            'unit_id'
        );
    }

    /**
     * Delegasi yang berasal dari assignment ini
     */
    public function delegations()
    {
        return $this->hasMany(
            PositionDelegation::class,
            'from_assignment_id'
        );
    }

    /**
     * Delegasi yang diterima
     */
    public function delegatedFrom()
    {
        return $this->hasMany(
            PositionDelegation::class,
            'to_assignment_id'
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

    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    public function subKategoriRkbuResponsibles()
    {
        return $this->hasMany(
            SubKategoriRkbuResponsible::class,
            'position_assignment_id'
        );
    }
}
