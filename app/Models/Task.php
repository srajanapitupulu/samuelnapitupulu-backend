<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Task extends Model
{
    use HasUuids;

    protected $fillable = [
        'team_id',
        'assigned_to',
        'created_by',
        'title',
        'description',
        'due_date',
        'status',
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    /* Relationships */

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /* Computed States */

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isNearDue(int $days = 3): bool
    {
        return $this->due_date
            && !$this->isCompleted()
            && Carbon::parse($this->due_date)->between(now(), now()->addDays($days));
    }

    public function isOverdue(): bool
    {
        return $this->due_date
            && !$this->isCompleted()
            && $this->due_date->isPast();
    }
}
