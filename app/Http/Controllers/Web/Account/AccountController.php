<?php

namespace App\Http\Controllers\Web\Account;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Account\Account;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\Factory;
use App\Http\Controllers\Web\Controller;
use App\Http\Requests\MakeAccountRequest;
use App\Http\Requests\AccessAccountRequest;
use App\Http\Requests\DeleteAccountRequest;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    /**
    * @param Request $request
    * @return Factory|View
    */
    public function _viewAccountsGet(Request $request): Factory|View
    {
        $this->vs->set('title', '- Account Management')
                 ->set('currentPage', 'page.accounts.list');

        return view('account.view_accounts');
    }

    /**
     * @todo -> this wants fleshing out, removal of the dump and die and actually display means of finding out what these
     *          details are for the account passed in question.
     *
     * @param Request $request
     * @param Account $account
     * @return Factory|View
     */
    public function _viewAccountGet(AccessAccountRequest $accountAccessHandler, Account $account): Factory|View
    {
        $this->vs->set('title', '- {Account} - Account Management')
                 ->set('currentPage', 'page.accounts.list');

        // dd(encrypt('00bicKjOAT06hPb6'));

        // handle the account access.
        $accountAccessHandler->handle();

        $account->load('access');

        $password = $account->getDecryptedPassword();
        $twoFactorRecovery = $account->getDecryptedTwoFactorAuthenticationRecovery();

        return view('account.view_account', compact(
            'account',
            'password',
            'twoFactorRecovery'
        ));
    }

    /**
    * @param Request $request
    * @return Factory|View
    */
    public function _ajaxViewAccountsGet(Request $request): Factory|View
    {
        $accounts = Account::query()
            ->where('user_id', '=', $this->vs->get('user')->id)
            ->orderBy('application')
            ->get();

        return view('account.ajax_view_accounts', compact(
            'accounts'
        ));
    }

    /**
    * @param MakeAccountRequest $request
    * @return JsonResponse
    */
    public function _ajaxMakeAccountsPost(MakeAccountRequest $handler): JsonResponse
    {
        $handler->handle();

        return response()->json([
            'success' => 'Account has been created'
        ]);
    }

    /**
    * @param Request $request
    * @return JsonResponse
    */
    public function _ajaxViewAccountsPasswordGet(Request $request): JsonResponse
    {
        $account_id = $request->input('account_id');
        $show       = $request->input('show');

        $account = Account::query()
            ->where('user_id', '=', Auth::id())
            ->where('id', '=', $account_id)
            ->first();

        return response()->json([
            'account_id' => $account_id,
            'password' => $show === "true"
                ? $account->getDecryptedPassword()
                : $account->getShortPassword()
        ]);
    }

    /**
    * @param DeleteAccountRequest $request
    * @return JsonResponse
    */
    public function _ajaxDeleteAccountsGet(DeleteAccountRequest $handler): JsonResponse
    {
        $handler->handle();

        return response()->json([
            'success' => 'Account has been deleted'
        ]);
    }
}
