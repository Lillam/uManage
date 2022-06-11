<?php

namespace App\Http\Controllers\Journal;

use Carbon\Carbon;
use App\Models\Journal\Journal;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Foundation\Application;

class JournalDashboardController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function _viewJournalsDashboardGet(): Application|Factory|View
    {
        $this->vs->set('title', 'Journals - Dashboard')
                 ->set('has_title', false)
                 ->set('current_page', 'page.journals.dashboard');

        $from = Carbon::now()->subDays(6);
        $to = Carbon::now()->subDay();

        $last_5_days = Journal::query()
            ->where('when', '>=', $from)
            ->where('when', '<=', $to)
            ->get()
            ->reverse();

        $good_days = Journal::query()
            ->where('rating', '=', 5)
            ->where('when', '>=', Carbon::now()->subYear())
            ->count();

        $bad_days = Journal::query()
            ->where('rating', '=', 1)
            ->where('when', '>=', Carbon::now()->subYear())
            ->count();

        $average_days = Journal::query()
            ->where('rating', '=', 3)
            ->where('when', '>=', Carbon::now()->subYear())
            ->count();

        return view('journal.view_journals_dashboard', compact(
            'last_5_days',
            'good_days',
            'bad_days',
            'average_days'
        ));
    }
}