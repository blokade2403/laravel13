<?php

namespace App\Models;

use App\Models\Workflow\WorkflowStep;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class WorkflowStepOfficialRole extends Model
{
    use HasFactory;

    protected $table = 'workflow_step_official_roles';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'workflow_step_id',
        'official_role_id',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $model) {
            if (empty($model->getKey())) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    // Relationships
    public function workflow_step()
    {
        return $this->belongsTo(WorkflowStep::class, 'workflow_step_id');
    }

    public function official_role()
    {
        return $this->belongsTo(OfficialRole::class, 'official_role_id');
    }
}
