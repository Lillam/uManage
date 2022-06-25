<?php

namespace App\Http\Controllers\Web\Journal;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Journal\JournalFinance;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Foundation\Application;

class JournalFinanceController extends Controller
{
    /**
    * @param Request $request
    * @return Application|Factory|View
    */
    public function _viewJournalFinancesGet(Request $request): Application|Factory|View
    {
        $date = ! empty($request->input('date'))
            ? Carbon::parse($request->input('date'))
            : Carbon::now();

        $this->vs->set('title', "Journal Finances - {$this->vs->get('user')->getFullName()}")
                  ->set('current_page', 'page.journals.finances.calendar');

        return view('journal.journal_finance.view_journal_finances', compact(
            'date'
        ));
    }

    /**
    * @param Request $request
    * @return JsonResponse
    */
    public function _ajaxViewJournalFinancesGet(Request $request): JsonResponse
    {
        $date = Carbon::parse($request->input('date'));

        $direction = $request->input('direction');

        if ($direction === 'left')
            $date = $date->subMonth();

        if ($direction === 'right')
            $date = $date->addMonth();

        $start_of_month = $date->startOfMonth();

        $days_in_month = $start_of_month->daysInMonth;

        $starting_day = $start_of_month->format('l');

        $days = Carbon::getDays();

        $dates = [];

        $day_key = array_search($starting_day, $days);

        $journal_finances = JournalFinance::select('*')
            ->where('user_id', '=', Auth::id())
            ->where('when', '>=', Carbon::parse($date)->startOfMonth()->format('Y-m-d'))
            ->where('when', '<=', Carbon::parse($date)->endOfMonth()->format('Y-m-d'))
            ->get();

        for ($day_increment = 1; $day_increment < ($days_in_month + 1); ++ $day_increment) {
            $journal_finance_key = "{$date->format('Y-m')}-" . (
                $day_increment < 10 ? '0' : ''
            ) . "$day_increment";

            // if the day key is 7, then we have reached the end and should be set back to the beginning. we don't have
            // a 7 value in the day key, and thus we are needing a reset.
            $day_key = $day_key === 7 ? 0 : $day_key;

            $dates[$journal_finance_key] = (object) [
                'title'  => "{$days[$day_key]} <span class='uk-text-small'>{$date->format('M')} $day_increment</span>",
                'gained' => 0,
                'lost'   => 0
            ];

            // increment the day key so that we are able to gather the next day in the iterable instance.
            $day_key += 1;
        }

        // we are going to iterate over all the journal finances; so that we're going to be able to get a grand total of
        // how much money was gained and spent on days in particular.
        foreach ($journal_finances as $journal_finance_key => $journal_finance) {
            if ($journal_finance->spend < 0)
                 $dates[$journal_finance->when->format('Y-m-d')]->lost   -= $journal_finance->spend;
            else $dates[$journal_finance->when->format('Y-m-d')]->gained += $journal_finance->spend;
            $journal_finances->forget($journal_finance_key);
        }

        return response()->json([
            'date'         => $date->format('Y-m'),
            'date_display' => $date->format('F Y'),
            'html'         => view('library.journal.journal_finance.ajax_view_journal_finances', compact(
                'dates'
            ))->render()
        ]);
    }
}