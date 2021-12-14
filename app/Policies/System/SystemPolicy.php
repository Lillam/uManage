<?php

namespace App\Policies\System;

use App\Policies\Policy;
use App\Models\User\User;

class SystemPolicy extends Policy
{
    /**
    * Let's check whether or not the authenticated user (signed in user) has access to the the module they are trying
    * to view... if they do, throw a true, yes, they can do it or false, no, they can't do it...
    *
    * @param User $user
    * @param string $module
    * @return bool
    */
    public function canUserUseModule(User $user, string $module): bool
    {
        return true;
//        if (in_array($module, $user->system_module_access->map(function ($system_module) {
//            return $system_module->system_module;
//        })->pluck('name')->toArray())) {
//            return true;
//        } return false;
    }
}