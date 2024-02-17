<?php

namespace App\Http\Controllers\Web\Account;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Account\Account;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\Factory;
use App\Http\Controllers\Web\Controller;

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
     * @param Request $request
     * @param Account $account
     * @return Factory|View
     */
    public function _viewAccountGet(Request $request, Account $account): Factory|View
    {
        $this->vs->set('title', '- {Account} - Account Management')
                 ->set('currentPage', 'page.accounts.list');
        
        dd($account->getDecryptedPassword(), $account->getDecryptedTwoFactorAuthenticationRecovery());

        return view('account.view_account', compact(
            'account'
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
    * @param Request $request
    * @return JsonResponse
    */
    public function _ajaxMakeAccountsPost(Request $request): JsonResponse
    {
        $user_id     = Auth::id();
        $account     = $request->input('account');
        $application = $request->input('application');
        $password    = $request->input('password');
        $order       = Account::query()->where('user_id', '=', $user_id)->count();

        Account::query()->create([
            'user_id'     => $user_id,
            'account'     => $account,
            'application' => $application,
            'password'    => encrypt($password),
            'two_factor_recovery_code' => '',
            'order'       => ($order + 1)
        ]);

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
            'password' => $show === "true" ?
                $account->getDecryptedPassword() :
                $account->getShortPassword()
        ]);
    }

    /**
    * @param Request $request
    * @return JsonResponse
    */
    public function _ajaxDeleteAccountsGet(Request $request): JsonResponse
    {
        Account::query()
            ->where('user_id', '=', Auth::id())
            ->where('id', '=', $request->input('account_id'))
            ->delete();

        return response()->json([
            'success' => 'Account has been deleted'
        ]);
    }
}
