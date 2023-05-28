<?php

namespace App\Http\Controllers\Web\Journal;

use Carbon\Carbon;
use App\Models\Journal\Journal;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use App\Http\Controllers\Web\Controller;
use Illuminate\Contracts\Foundation\Application;

class JournalDashboardController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function _viewJournalsDashboardGet(): Application|Factory|View
    {
        $this->vs->set('title', 'Journals - Dashboard')
                 ->set('hasTitle', false)
                 ->set('currentPage', 'page.journals.dashboard');

        $from = Carbon::now()->subDays(5);
        $to = Carbon::now();

        $last5Days = Journal::query()
            ->where('when', '>=', $from)
            ->where('when', '<=', $to)
            ->get()
            ->reverse();

        $goodDays = Journal::query()
            ->where('rating', '=', 5)
            ->where('when', '>=', Carbon::now()->subYear())
            ->count();

        $badDays = Journal::query()
            ->where('rating', '=', 1)
            ->where('when', '>=', Carbon::now()->subYear())
            ->count();

        $averageDays = Journal::query()
            ->where('rating', '=', 3)
            ->where('when', '>=', Carbon::now()->subYear())
            ->count();

        return view('journal.view_journals_dashboard', compact(
            'last5Days',
            'goodDays',
            'badDays',
            'averageDays'
        ));
    }
}