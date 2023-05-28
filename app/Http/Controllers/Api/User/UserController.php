<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Web\Controller;
use App\Models\User\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            /** @var User $user */
            ($user = Auth::user())->load('systemModuleAccess');
            return response()->json($user);
        }

        return response()->json([
            'error' => 'Email and Password combination incorrect'
        ]);
    }

    public function view(): JsonResponse
    {
        return response()->json(User::all());
    }
}