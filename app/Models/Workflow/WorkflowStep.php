<?php

namespace App\Models\Workflow;

use App\Models\MasterBackend\SettingUser\OfficialRole;
use App\Models\MasterBackend\SettingUser\Position;
use App\Models\WorkflowStepCondition;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkflowStep extends Model
{
    use HasUuids;

    protected $table = 'workflow_steps';

    protected $fillable = [
        'workflow_id',
        'workflow_version_id',
        'name',
        'step_order',
        'approval_type',
        'position_id',
        'official_role_id',
        'role_id',
        'hierarchy_depth',
        'target_scope_type',
        'target_unit_id',
        'allow_skip_same_user',
        'can_delegate',
        'is_required',
    ];

    protected $casts = [
        'allow_skip_same_user' => 'boolean',
        'can_delegate' => 'boolean',
        'is_required' => 'boolean',
    ];

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(
            Workflow::class,
            'workflow_id'
        );
    }

    public function version(): BelongsTo
    {
        return $this->belongsTo(
            WorkflowVersion::class,
            'workflow_version_id'
        );
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(
            Position::class,
            'position_id'
        );
    }

    public function officialRole(): BelongsTo
    {
        return $this->belongsTo(
            OfficialRole::class,
            'official_role_id'
        );
    }

    public function conditions(): HasMany
    {
        return $this->hasMany(
            WorkflowStepCondition::class,
            'workflow_step_id'
        );
    }
}
