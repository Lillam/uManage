<?php

namespace App\Policies\User;

use App\Policies\Policy;
use App\Models\User\User;

class UserPolicy extends Policy
{
    /**
    * Permission to check to see whether or not the authenticated user is able to view the particular user (this will
    * require checking to see if the user is their own user, or if the user that is trying to look has any form of
    * permission in place that grants them the ability to do so)
    *
    * @param User $self
    * @param User $user
    * @return bool
    */
    public function viewUser(User $self, User $user): bool
    {
        return $self->id === 1 ||
               $self->id === $user->id;
    }

    /**
    * Permission to check to see whether or not the authenticated user is able to edit the particular user (this will
    * require checking to see if the user is their own user, or or if the user that is trying to edit has any form of
    * permission in place that grants them the ability to do so).
    *
    * @param User $self
    * @param User $user
    * @return bool
    */
    public function editUser(User $self, User $user): bool
    {
        return $self->id === 1 ||
               $self->id === $user->id;
    }
}