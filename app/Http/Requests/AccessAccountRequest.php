<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use App\Models\Account\AccountAccess;

class AccessAccountRequest extends HandleRequest
{
    public function handle(): bool
    {
        $id = str_replace('/accounts/', '', $this->request->getRequestUri());

        // increment the access every time that someone is looking at it -
        // could potentially have this store other information like; what IP had viewed it
        // and then aggregate those results into a table or something to view as a whole but
        // given the application is only really for me at the current moment in time;
        // might as well just not.
        if ($this->doesNotExist($id)) {
            AccountAccess::create([ 'account_id' => $id, 'user_id' => Auth::id(), 'access_count' => 1 ]);
            return true;
        }

        AccountAccess::query()->where('account_id', '=', $id)
                              ->where('user_id', '=', Auth::id())
                              ->increment('access_count');

        return true;
    }

    /**
     * Check to see whether the account access already exists or not.
     * @todo -> this could be potentially redundant if one just creates the account access with an id;
     *          load the relationship and increment the field and then save. (making this function
     *          redundant)
     *
     * @param int $id
     * @return bool
     */
    private function doesNotExist(int $id): bool
    {
        return AccountAccess::query()->where('account_id', '=', $id)
                                     ->where('user_id', '=', Auth::id())
                                     ->doesntExist();
    }
}
