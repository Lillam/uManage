<?php

namespace App\Policies\Timelog;

use App\Policies\Policy;
use App\Models\User\User;
use App\Models\TimeLog\TimeLog;

class TimelogPolicy extends Policy
{
    /**
    * A gate method which will decide whether or not the authenticated user can actually edit the TimeLog in question
    * taking a parameter of self (the authenticated user) as well as the TimeLog in question and checking whether or
    * not the user is the creator of the timelog entry.
    *
    * @param User $self
    * @param TimeLog $timelog
    * @return bool
    */
    public function editTimelog(User $self, TimeLog $timelog): bool
    {
        return $self->id === $timelog->user_id;
    }
}