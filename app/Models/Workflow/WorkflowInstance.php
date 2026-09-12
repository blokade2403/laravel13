<?php

namespace App\Models\Workflow;

use App\Models\MasterBackend\SettingUser\PositionAssignment;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkflowInstance extends Model
{
    use HasUuids;

    protected $table = 'workflow_instances';

    protected $fillable = [
        'workflow_id',
        'workflow_version_id',
        'source_assignment_id',
        'document_type',
        'document_id',
        'status',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
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

    public function sourceAssignment(): BelongsTo
    {
        return $this->belongsTo(
            PositionAssignment::class,
            'source_assignment_id'
        );
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(
            Approval::class,
            'workflow_instance_id'
        );
    }
}
