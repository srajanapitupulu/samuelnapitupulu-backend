<?php

namespace App\Services;

use App\Models\Invitation;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class InvitationService
{
    public function create(array $data): Invitation
    {
        return Invitation::create([
            'team_id' => $data['team_id'],
            'invited_by' => $data['invited_by'],
            'phone' => $data['phone'] ?? null,
            'token' => Str::random(64),
            'status' => 'pending',
        ]);
    }

    public function markAccepted(Invitation $invitation): void
    {
        $invitation->update(['status' => 'accepted']);
    }

    public function markDeclined(Invitation $invitation): void
    {
        $invitation->update(['status' => 'declined']);
    }
}
