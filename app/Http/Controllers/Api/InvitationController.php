<?php

namespace App\Http\Controllers\Api;

use App\Models\Invitation;
use App\Services\InvitationService;
use Illuminate\Http\Request;

class InvitationController extends ApiController
{
    public function index()
    {
        $this->authorize('viewAny', Invitation::class);

        return $this->success(
            Invitation::where('team_id', request()->user()->team_id)->latest()->get(),
            'Invitations'
        );
    }

    public function store(Request $request, InvitationService $service)
    {
        $this->authorize('create', Invitation::class);

        $request->validate([
            'phone' => ['nullable', 'string'],
        ]);

        $invitation = $service->create([
            'team_id' => $request->user()->team_id,
            'invited_by' => $request->user()->id,
            'phone' => $request->phone,
        ]);

        return $this->success($invitation, 'Invitation sent');
    }
}
