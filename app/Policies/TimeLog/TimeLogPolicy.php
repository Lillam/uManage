<?php

namespace App\Policies\TimeLog;

use App\Policies\Policy;
use App\Models\User\User;
use App\Models\TimeLog\TimeLog;

class TimeLogPolicy extends Policy
{
    /**
    * A gate method which will decide whether  the authenticated user can actually edit the TimeLog in question
    * taking a parameter of self (the authenticated user) as well as the TimeLog in question or not, and checking
     * whether the user is the creator of the time_log entry or not.
    *
    * @param User $self
    * @param TimeLog $time_log
    * @return bool
    */
    public function editTimeLog(User $self, TimeLog $time_log): bool
    {
        return $self->id === $time_log->user_id;
    }
}