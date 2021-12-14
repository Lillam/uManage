<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User\User;
use Illuminate\Support\Facades\Auth;

class AuthenticateUser
{
    /**
    * This method is going to be capturing all there is to know about the user, this is going to be capturing all of
    * the user in question's data, such as their access, their projects etc... this method is also going to be
    * appending the data into the view service so that we can utilise it anywhere across the system.
    *
    * @param $request
    * @param Closure $next
    * @return mixed
    */
    public function handle($request, Closure $next)
    {
        // step one, we are going to check whether or not the user has been signed in... and if that is the case... then
        // we are going to do a variety of things to the users models, so that we can pass this around the entire system
        // store this in a variable that will be assigned into the make view service set user.
        $user = Auth::user();

        // if the user happens to be an instance of user; aka, the user has been signed in; we are going to load the
        // relationships for the system module access so that we can begin to map it to the user as a better array
        // and sort it so we have a better method of reference later on in the application.
        if ($user instanceof User) {
            $user->load('system_module_access', 'system_module_access.system_module');
            // we are going to apply all of the modules that the user has access to against them, this will take only
            // unique values out of the system module access; so that we can just simply return a small collection of
            // elements.
            $user->system_modules = $user->system_module_access->map(function ($system_module_access) {
                return $system_module_access->system_module;
            })->unique();

            // this will map the entire collection of user's access and place it into system_access associative array
            // so we are going to be able to utilise for quick access checking.
            $user->system_access = array_flip($user->system_module_access->map(function ($system_module_access) {
                return $system_module_access->getControllerMethod();
            })->toArray()); unset($user->system_module_access);
        }

        // apply the user, whether a user or null; we're going to assign it into the view service... so that we will
        // have access to everything that had been set in this middleware controller.
        (app('vs'))->set('user', $user);

        return $next($request);
    }
}