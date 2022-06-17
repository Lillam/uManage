<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Foundation\Application;

class AccountReportController extends Controller
{
    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function _viewAccountsReportGet(Request $request): Application|Factory|View
    {
        return view('accounts.report.view_accounts_report');
    }
}