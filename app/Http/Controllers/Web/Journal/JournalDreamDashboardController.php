<?php

namespace App\Http\Controllers\Web\Journal;

use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use App\Http\Controllers\Web\Controller;
use Illuminate\Contracts\Foundation\Application;

class JournalDreamDashboardController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function _viewJournalsDreamsDashboardGet(): Application|Factory|View
    {
        $this->vs->set('currentPage', 'page.journals.dreams.dashboard');

        return view('journal.journal_dream.view_journal_dreams_dashboard');
    }
}
