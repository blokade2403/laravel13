<?php

namespace App\Models;

use App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu;
use App\Models\MasterBackend\SettingUser\Fase;
use App\Models\MasterBackend\SettingUser\Unit;
use App\Models\Workflow\WorkflowStep;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class WorkflowStepCondition extends Model
{
    use HasFactory;

    protected $table = 'workflow_step_conditions';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'workflow_step_id',
        'sub_kategori_rkbu_id',
        'unit_id',
        'fase_id',
        'minimal_nominal',
        'maksimal_nominal',
        'field_name',
        'operator',
        'field_value',
    ];

    protected function casts(): array
    {
        return [
            'minimal_nominal' => 'decimal:2',
            'maksimal_nominal' => 'decimal:2',
        ];
    }

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

    public function sub_kategori_rkbu()
    {
        return $this->belongsTo(SubKategoriRkbu::class, 'sub_kategori_rkbu_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function fase()
    {
        return $this->belongsTo(Fase::class, 'fase_id');
    }
}
