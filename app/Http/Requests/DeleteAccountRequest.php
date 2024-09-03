<?php

namespace App\Http\Requests;

use App\Models\Account\Account;

class DeleteAccountRequest extends HandleRequest
{
    public function handle(): bool
    {
        Account::query()
            ->where('user_id', '=', $this->getUserId())
            ->where('id', '=', $this->request->input('account_id'))
            ->delete();

        return true;
    }
}
