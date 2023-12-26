<?php

namespace App\Http\Controllers\Api\User;

use App\Models\User\User;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Web\Controller;
use App\Http\Requests\User\HandleUserLoginRequest;

class UserController extends Controller
{
    public function login(HandleUserLoginRequest $handler): JsonResponse
    {
        return $handler->handle();
    }

    public function view(): JsonResponse
    {
        return response()->json(User::all());
    }
}