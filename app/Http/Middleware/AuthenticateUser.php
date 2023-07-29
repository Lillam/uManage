<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User\User;
use Illuminate\Support\Facades\Auth;
use App\Models\System\SystemModuleAccess;

class AuthenticateUser
{
    /**
    * This method is going to be capturing all there is to know about the user, this is going to be capturing all the
    * user in question's data, such as their access, their projects etc... this method is also going to be appending
    * the data into the view service so that we can utilise it anywhere across the system.
    *
    * @param $request
    * @param Closure $next
    * @return mixed
    */
    public function handle($request, Closure $next): mixed
    {
        // step one, we are going to check whether the user has been signed in or not... and if that is the case... then
        // we are going to do a variety of things to the users models, so that we can pass this around the entire system
        // store this in a variable that will be assigned into the make view service set user.
        $user = Auth::user();

        // if the user happens to be an instance of user; aka, the user has been signed in; we are going to load the
        // relationships for the system module access so that we can begin to map it to the user as a better array
        // and sort it so that we have a better method of reference later on in the application.
        if ($user instanceof User) {
            $user->load('systemModuleAccess', 'systemModuleAccess.systemModule');
            // we are going to apply all the modules that the user has access to against them, this will take only
            // unique values out of the system module access; so that we can just simply return a small collection of
            // elements.
            $user->system_modules = $user->systemModuleAccess->map(
                function (SystemModuleAccess $systemModuleAccess) {
                    return $systemModuleAccess->systemModule;
                }
            )->unique();

            // this will map the entire collection of user's access and place it into system_access associative array
            // so that we are going to be able to utilise for quick access checking.
            $user->systemAccess = array_flip(
                $user->systemModuleAccess->map(function (SystemModuleAccess $systemModuleAccess) {
                    return $systemModuleAccess->getControllerMethod();
                })->toArray()
            );

            // let go of the system module access property on the user; as we have attached a more elegant structure
            // to the user to use later on in the flow of the user interaction.
            unset($user->systemModuleAccess);
        }

        // apply the user, whether a user or null; we're going to assign it into the view service... so that we will
        // have access to everything that had been set in this middleware controller.
        (app('vs'))->set('user', $user)
                            ->set('applicationTheme', $user->userSetting->theme_color ?? 'light-theme');

        return $next($request);
    }
}