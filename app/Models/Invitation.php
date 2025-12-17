<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Invitation extends Model
{
    use HasUuids;

    protected $fillable = [
        'team_id',
        'invited_by',
        'phone',
        'token',
        'status',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function inviter()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }
}
