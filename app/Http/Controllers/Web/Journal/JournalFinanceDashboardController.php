<?php

namespace App\Http\Controllers\Web\Journal;

use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Foundation\Application;

class JournalFinanceDashboardController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function _viewJournalsFinancesDashboardGet(): Application|Factory|View
    {
        $this->vs->set('current_page', 'page.journals.finances.dashboard');

        return view('journal.journal_finance.view_journal_finances_dashboard');
    }
}