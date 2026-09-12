<?php

namespace App\Models\MasterBackend\UserProfil;

use App\Models\MasterBackend\SettingUser\PositionAssignment;
use App\Models\MasterBackend\SettingUser\Role;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, HasUuids, Notifiable;

    protected $table = 'users';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'username',
        'nip',
        'nama',
        'email',
        'password',
        'status_user',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function positionAssignments()
    {
        return $this->hasMany(
            PositionAssignment::class,
            'user_id'
        );
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_assignments')
            ->wherePivot('is_active', true)
            ->withPivot([
                'unit_id',
                'tanggal_mulai',
                'tanggal_selesai',
                'is_active',
            ])
            ->withTimestamps();
    }

    /**
     * Assignment aktif saat ini
     */
    public function activePositionAssignments()
    {
        return $this->hasMany(
            PositionAssignment::class,
            'user_id'
        )
            ->where('is_active', true)
            ->whereDate('tanggal_mulai', '<=', now())
            ->where(function ($query) {
                $query->whereNull('tanggal_selesai')
                    ->orWhereDate('tanggal_selesai', '>=', now());
            });
    }

    /**
     * Jabatan utama user
     */
    public function primaryPositionAssignment()
    {
        return $this->hasOne(
            PositionAssignment::class,
            'user_id'
        )
            ->where('is_primary', true)
            ->where('is_active', true)
            ->whereDate('tanggal_mulai', '<=', now())
            ->where(function ($query) {
                $query->whereNull('tanggal_selesai')
                    ->orWhereDate('tanggal_selesai', '>=', now());
            });
    }
}
