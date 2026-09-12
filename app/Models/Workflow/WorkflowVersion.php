<?php

namespace App\Models\Workflow;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkflowVersion extends Model
{
    use HasUuids;

    protected $table = 'workflow_versions';

    protected $fillable = [
        'workflow_id',
        'version_no',
        'name',
        'description',
        'status',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(
            Workflow::class,
            'workflow_id'
        );
    }

    public function steps(): HasMany
    {
        return $this->hasMany(
            WorkflowStep::class,
            'workflow_version_id'
        )->orderBy('step_order');
    }
}
