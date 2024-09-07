<?php

namespace App\Http\Controllers\Web\Account;

use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use App\Http\Controllers\Web\Controller;
use Illuminate\Contracts\Foundation\Application;

class AccountReportController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function _viewAccountsReportGet(): Application|Factory|View
    {
        return view('accounts.report.view_accounts_report');
    }
}
