<?php

namespace App\Models\MasterBackend\SettingUser;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PositionDelegation extends Model
{
    use HasFactory;

    protected $table = 'position_delegations';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'position_id',
        'from_assignment_id',
        'to_assignment_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'alasan',
        'nomor_sk',
        'tanggal_sk',
        'is_active',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'tanggal_sk' => 'date',
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
     * Assignment pejabat yang mendelegasikan
     */
    public function fromAssignment()
    {
        return $this->belongsTo(
            PositionAssignment::class,
            'from_assignment_id'
        );
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    /**
     * Assignment pejabat penerima delegasi
     */
    public function toAssignment()
    {
        return $this->belongsTo(
            PositionAssignment::class,
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
}
