<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\Room;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RoomPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function index(User $user, Room $room): Response
    {
        if ($user->role_id != Role::OWNER) {
            return Response::allow();
        }

        return $user->id == $room->owner_id
            ? Response::allow()
            : Response::denyAsNotFound('You are not the owner of this room.');
    }
}
