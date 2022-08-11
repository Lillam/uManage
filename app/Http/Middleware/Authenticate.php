<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use App\Http\Controllers\Web\User\UserController;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
    * Get the path the user should be redirected to when they are not authenticated.
    *
    * @param  Request  $request
    * @return string|null
    */
    protected function redirectTo($request): string|null
    {
        return ! $request->expectsJson()
            ? action([UserController::class, '_viewUserLoginGet'])
            : null;
    }
}
