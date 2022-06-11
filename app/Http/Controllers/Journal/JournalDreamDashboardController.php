<?php

namespace App\Http\Controllers\Journal;

use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Foundation\Application;

class JournalDreamDashboardController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function _viewJournalsDreamsDashboardGet(): Application|Factory|View
    {
        $this->vs->set('current_page', 'page.journals.dreams.dashboard');

        return view('journal.journal_dream.view_journal_dreams_dashboard');
    }
}