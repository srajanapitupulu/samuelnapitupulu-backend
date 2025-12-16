<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Services\UserService;
use App\Http\Requests\Api\StoreUserRequest;
use App\Http\Requests\Api\UpdateUserRequest;

class UserController extends ApiController
{
    public function index(UserService $service)
    {
        $this->authorize('viewAny', User::class);

        return $this->success(
            $service->paginate(),
            'User list'
        );
    }

    public function store(StoreUserRequest $request)
    {
        $this->authorize('create', User::class);

        $authUser = auth()->guard()->user();
        $data = $request->validated();

        // Team leader restrictions
        if ($authUser->isTeamLeader()) {
            $data['role'] = User::ROLE_TEAM_MEMBER;
            $data['team_id'] = $authUser->team_id;
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role' => $data['role'],
            'team_id' => $data['team_id'] ?? null,
        ]);

        return $this->success($user, 'User created');
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);

        return $this->success($user, 'User detail');
    }

    public function update(UpdateUserRequest $request, User $user, UserService $service)
    {
        $this->authorize('update', $user);

        $data = $request->validated();

        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        // Non-admins cannot change roles
        if (!auth()->guard()->user()->isAdmin()) {
            unset($data['role']);
        }

        return $this->success(
            $service->update($user, $data),
            'User updated'
        );
    }

    public function destroy(User $user, UserService $service)
    {
        $this->authorize('delete', $user);

        $service->delete($user);

        return $this->success(null, 'User deleted');
    }
}
