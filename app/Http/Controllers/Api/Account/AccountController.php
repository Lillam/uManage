<?php

namespace App\Http\Controllers\Api\Account;

use App\Models\Account\Account;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Api\Controller;

class AccountController extends Controller
{
    public function list(): JsonResponse
    {
        return $this->respond(Account::all());
    }
}