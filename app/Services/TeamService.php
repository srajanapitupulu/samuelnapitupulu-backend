<?php

namespace App\Services;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TeamService
{
    public function paginate(int $perPage = 15)
    {
        return Team::query()->paginate($perPage);
    }

    public function create(array $data): Team
    {
        return DB::transaction(function () use ($data) {
            $team = Team::create([
                'name' => $data['name'],
                'leader_id' => $data['leader_id'] ?? null,
            ]);

            if (!empty($data['leader_id'])) {
                User::where('id', $data['leader_id'])->update([
                    'team_id' => $team->id,
                    'role' => User::ROLE_TEAM_LEADER,
                ]);
            }

            return $team->refresh();
        });
    }

    public function update(Team $team, array $data): Team
    {
        $team->update($data);
        return $team->refresh();
    }

    public function delete(Team $team): void
    {
        DB::transaction(function () use ($team) {
            User::where('team_id', $team->id)->update([
                'team_id' => null,
                'role' => User::ROLE_TEAM_MEMBER,
            ]);

            $team->delete();
        });
    }
}
