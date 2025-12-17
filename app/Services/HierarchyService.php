<?php

namespace App\Services;

use App\Models\User;
use App\Models\Team;

class HierarchyService
{
    public function forUser(User $user): array
    {
        if (!$user->team_id) {
            return [
                'team' => null,
                'leader' => null,
                'members' => [],
            ];
        }

        $team = Team::with(['leader', 'members'])
            ->where('id', $user->team_id)
            ->firstOrFail();

        return [
            'team' => [
                'id' => $team->id,
                'name' => $team->name,
            ],
            'leader' => $team->leader ? [
                'id' => $team->leader->id,
                'name' => $team->leader->name,
                'email' => $team->leader->email,
            ] : null,
            'members' => $team->members->map(fn($member) => [
                'id' => $member->id,
                'name' => $member->name,
                'email' => $member->email,
                'role' => $member->role,
            ])->values(),
        ];
    }
}
