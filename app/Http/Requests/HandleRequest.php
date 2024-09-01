<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User;

abstract class HandleRequest
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get the user that's from the request (utilising Auth facade)
     *
     * @return User
     */
    protected function getUser(): User
    {
        return Auth::user();
    }

    /**
     * Get the user id that's from the request (utilising Auth facade)
     *
     * @return int|string
     */
    protected function getUserId(): int | string
    {
        return Auth::id();
    }

    abstract function handle(): bool;
}
