<?php

namespace App\Policies;

use App\Models\User\User;

class Policy
{
    /**
    * Policy constructor.
    */
    public function __construct() {}

    public function canI(User $self)
    {
        return true;
    }
}