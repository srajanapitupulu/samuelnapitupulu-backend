<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Http\Requests\Api\StoreTaskRequest;
use App\Http\Requests\Api\UpdateTaskRequest;
use App\Services\TaskService;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $tasks = Task::where('team_id', $user->team_id)
            ->when(
                !$user->isTeamLeader(),
                fn($q) =>
                $q->where('assigned_to', $user->id)
            )
            ->latest()
            ->get()
            ->map(fn($task) => [
                'id' => $task->id,
                'title' => $task->title,
                'status' => $task->status,
                'due_date' => $task->due_date,
                'near_due' => $task->isNearDue(),
                'overdue' => $task->isOverdue(),
            ]);

        return $this->success($tasks, 'Tasks');
    }

    public function store(StoreTaskRequest $request, TaskService $service)
    {
        $this->authorize('create', Task::class);

        $task = $service->create($request->user(), $request->validated());

        return $this->success($task, 'Task created');
    }

    public function update(
        UpdateTaskRequest $request,
        Task $task,
        TaskService $service
    ) {
        $this->authorize('update', $task);

        if ($request->status === 'completed') {
            $task = $service->markCompleted($task);
        }

        return $this->success($task, 'Task updated');
    }
}
