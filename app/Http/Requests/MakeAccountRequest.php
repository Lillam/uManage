<?php

namespace App\Http\Requests;

use App\Models\Account\Account;

class MakeAccountRequest extends HandleRequest
{
    public function handle(): bool
    {
        Account::query()->create([
            'user_id' => $this->getUserId(),
            'account' => $this->request->input('account'),
            'application' => $this->request->input('application'),
            'pasword' => $this->request->input('password'),
            'two_factor_recovery_code' => '',
            'order' => Account::query()->where('user_id', '=', $this->getUserId())->count() + 1
        ]);

        return true;
    }
}
