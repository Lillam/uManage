<?php

namespace App\Http\Requests\User;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class HandleUserLoginRequest
{
    protected Request $request;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the user's login attempt.
     *
     * @return JsonResponse
     */
    public function handle(): JsonResponse
    {
        if (Auth::attempt($this->request->only('email', 'password'))) {
            return response()->json(
                Auth::user()->load('systemModuleAccess')
            );
        }

        return response()->json([
            'error' => 'Email and Password combination incorrect'
        ]);
    }
}