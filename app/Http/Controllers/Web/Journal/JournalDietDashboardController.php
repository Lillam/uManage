<?php

namespace App\Http\Controllers\Web\Journal;

use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use App\Http\Controllers\Web\Controller;
use App\Models\Journal\JournalDietItem;
use Illuminate\Contracts\Foundation\Application;

class JournalDietDashboardController extends Controller
{
    public function _viewJournalDietDashboardGet(): Application|Factory|View
    {
        $journalDietItems = JournalDietItem::all();

        $this->vs->set('title', 'Journal - Diet Dashboard')
                 ->set('hasTitle', true)
                 ->set('currentPage', 'page.journals.diet.dashboard');

        return view('journal.journal_diet.view_journal_diet_dashboard', compact(
            'journalDietItems'
        ));
    }
}
