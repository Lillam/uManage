<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerExceptionInterface;

class AuthenticateUserModuleAccess
{
    /**
    * The target that the user is trying to access. This will be identified against the user's access and if the route
    * sites in there then they will be continued on.
    *
    * @var string|mixed
    */
    private string $target;

    public function __construct()
    {
        $this->target = Route::getCurrentRoute()->action['controller'];
    }

    /**
    * This method is dedicated to checking whether the user in question has access to the page that they're trying to
    * view or not, so I'm going to need to debug trace back to a point where we can check if the user falls into the
    * stack; if this entry is inside the database, then we can inform the system that they're good to let through.
    * his will be something that might be a beneficial caching so that it doesn't have to do this every single time.
    *
    * @param $request
    * @param Closure $next
    * @return Closure|Response|JsonResponse|RedirectResponse
    * @throws ContainerExceptionInterface
    * @throws NotFoundExceptionInterface
    */
    public function handle($request, Closure $next): Closure|Response|JsonResponse|RedirectResponse
    {
        // if the user has the controller access; then we are going to allow the user the ability to display this on
        // the frontend; and return the next request as the user has already been confirmed the permission set here.
        if (array_key_exists(
            $this->target,
            app('vs')->get('user')->system_access
        )) return $next($request);

        // if we don't manage to prove that the user has access to a module; then we are going to simply return a 403.
        // that the user does not have access to see it.
        abort(403, $this->target);
    }
}