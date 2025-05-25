<?php

namespace App\Policies;

use App\Models\User;
use App\Models\event;
use Illuminate\Auth\Access\Response;

class eventPolicy
{
    public function is_creator_of_event( User $user , $event)
    {
    return $user->id === $event->user->id;
    }
}
