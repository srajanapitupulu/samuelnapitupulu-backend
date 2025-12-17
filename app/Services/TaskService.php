<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;

class TaskService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
    }

    public function create(User $leader, array $data): Task
    {
        return Task::create([
            'team_id' => $leader->team_id,
            'created_by' => $leader->id,
            'assigned_to' => $data['assigned_to'],
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'due_date' => $data['due_date'] ?? null,
        ]);
    }

    public function markCompleted(Task $task): Task
    {
        $task->update(['status' => 'completed']);
        return $task->refresh();
    }
}
