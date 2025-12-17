<?php

namespace App\Http\Controllers\Api;

use App\Models\Team;
use App\Services\TeamService;
use App\Http\Requests\Api\StoreTeamRequest;
use App\Http\Requests\Api\UpdateTeamRequest;

class TeamController extends ApiController
{
    public function index(TeamService $service)
    {
        $this->authorize('viewAny', Team::class);

        return $this->success(
            $service->paginate(),
            'Team list'
        );
    }

    public function store(StoreTeamRequest $request, TeamService $service)
    {
        $this->authorize('create', Team::class);

        $team = $service->create($request->validated());

        return $this->success($team, 'Team created');
    }

    public function show(Team $team)
    {
        $this->authorize('view', $team);

        return $this->success($team, 'Team detail');
    }

    public function update(
        UpdateTeamRequest $request,
        Team $team,
        TeamService $service
    ) {
        $this->authorize('update', $team);

        $team = $service->update($team, $request->validated());

        return $this->success($team, 'Team updated');
    }

    public function destroy(Team $team, TeamService $service)
    {
        $this->authorize('delete', $team);

        $service->delete($team);

        return $this->success(null, 'Team deleted');
    }
}
