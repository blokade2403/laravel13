<?php

namespace App\Models\MasterBackend\UserProfil;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserValidatorRole extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'user_id',
        'validator_role_id',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function validatorRole()
    {
        return $this->belongsTo(
            ValidatorRole::class,
            'validator_role_id'
        );
    }
}
