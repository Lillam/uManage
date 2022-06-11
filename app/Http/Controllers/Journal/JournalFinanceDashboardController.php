<?php

namespace App\Http\Controllers\Journal;

use App\Http\Controllers\Controller;

class JournalFinanceDashboardController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function _viewJournalsFinancesDashboardGet()
    {
        $this->vs->set('current_page', 'page.journals.finances.dashboard');

        return view('journal.journal_finance.view_journal_finances_dashboard');
    }
}