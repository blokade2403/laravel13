<?php

namespace App\Models\Workflow;

use App\Models\MasterBackend\SettingUser\OfficialRole;
use App\Models\MasterBackend\SettingUser\Position;
use App\Models\MasterBackend\SettingUser\PositionAssignment;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Approval extends Model
{
    use HasUuids;

    protected $table = 'approvals';

    protected $fillable = [
        'workflow_instance_id',
        'workflow_step_id',
        'target_position_id',
        'target_assignment_id',
        'target_official_role_id',
        'assigned_user_id',
        'status',
        'catatan',
        'assigned_at',
        'approved_at',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function workflowInstance(): BelongsTo
    {
        return $this->belongsTo(
            WorkflowInstance::class,
            'workflow_instance_id'
        );
    }

    public function workflowStep(): BelongsTo
    {
        return $this->belongsTo(
            WorkflowStep::class,
            'workflow_step_id'
        );
    }

    public function targetPosition(): BelongsTo
    {
        return $this->belongsTo(
            Position::class,
            'target_position_id'
        );
    }

    public function targetAssignment(): BelongsTo
    {
        return $this->belongsTo(
            PositionAssignment::class,
            'target_assignment_id'
        );
    }

    public function targetOfficialRole(): BelongsTo
    {
        return $this->belongsTo(
            OfficialRole::class,
            'target_official_role_id'
        );
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'assigned_user_id'
        );
    }

    public function logs(): HasMany
    {
        return $this->hasMany(
            ApprovalLog::class,
            'approval_id'
        );
    }
}
