<?php

namespace App\Http\Controllers\Journal;

use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Journal\Journal;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\Factory;

class JournalReportController extends Controller
{
    /**
    * This method is specifically for showing the daily progress of the users ratings. this will be a page to highlight
    * occurrences quickly throughout the month, how much has been rated in comparison to how many achievements happened
    * that day.
    *
    * @param Request $request
    * @return Factory|View
    */
    public function _viewJournalsReportGet(Request $request): Factory|View
    {
        // lets check whether or not we have a date passed through the url parameters, if we do then we are going to be
        // parsing this as a date in the format of Year Month Day. otherwise, we are going to take todays date in the
        // format of Year Month Day.
        $date = $request->input('date');
        $date = ! empty($date)
            ? Carbon::parse($date)
            : Carbon::now();

        $this->vs->set('title', "Journal Report - {$this->vs->get('user')->getFullName()}")
            ->set('current_page', 'page.journals.report');

        return view('journal.journal_report.view_journal_report', compact(
            'date'
        ));
    }

    /**
    * This method is entirely for grabbing journals in report format, this will be returning all the data in a json
    * oriented object so that the frontend can process what is going on here; all values will be viewed inside the
    * view_journals_report.js file, after ajaxing has happened. This method is entirely a cycle, and for as many times
    * as the user will keep clicking next or back, will depend on the values that get returned (based on the past
    * date)
    *
    * @param Request $request
    * @return JsonResponse
    */
    public function _ajaxViewJournalsReportGet(Request $request): JsonResponse
    {
        // Acquiring the date that the report is going to be generated off; the user will be able to cycle through the
        // months, and in doing so, providing there is a month set, we will be utilising this month, otherwise, this
        // month will be set to today.
        $date = Carbon::parse($request->input('date'));

        // checking to see whether we have a direction or not, by default unless requested by the user this will be null
        // or '', and if this is the case we are going to iterate on over past this logic. however if it is set then
        // we are either going to be incrementing a month, or decrementing a month and then returning this specific
        // date and altering it for when we grab the necessary data for the month in question.
        $direction = $request->input('direction');

        // if the direction has been marked as left, then we are going back in time; it'll take this month and then sub
        // a month
        if (! empty($direction) && $direction === 'left')
            $date = $date->subMonth();

        // if the direction has been marked as right, then we are going forward in time; it'll take this month and then
        // add a month
        if (! empty($direction) && $direction === 'right')
            $date = $date->addMonth();

        // acquire all the journals that have a when between the start and end of the month for the month of which has
        // been designated above.
        $journals = Journal::where('user_id', '=', Auth::id())
            ->with('achievements')
            ->where('when', '>=', Carbon::parse($date)->startOfMonth())
            ->where('when', '<=', Carbon::parse($date)->endOfMonth())
            ->get();

        // pre-defining all the variables that this method is going to be returning to the client, allowing the
        // javascript to take effect at this point. all variables will be defined a default so that we have the knowledge
        // of knowing that the values are in fact set.
        $journal_labels                     = [];
        $journal_ratings                    = [];
        $journal_ratings_colors             = [];
        $journal_achievements               = [];
        $journal_achievements_colors        = [];
        $journal_highest_word_counts        = [];
        $journal_highest_word_counts_colors = [];
        $journal_lowest_word_counts         = [];
        $journal_lowest_word_counts_colors  = [];

        // pre-defining all the variables in respect to the statistic set of data, all of this will be defaulted to 0
        // so that when we are referring to this back in the code, it won't flag up as undefined, but simply instead
        // print out a 0, as there will be no information on the respective variables.
        $total_achievements                 = 0;
        $total_1_star_days                  = 0;
        $total_2_star_days                  = 0;
        $total_3_star_days                  = 0;
        $total_4_star_days                  = 0;
        $total_5_star_days                  = 0;

        foreach ($journals as $key => $journal) {
            $journal_labels[]                     = $journal->when->format('d-m-Y');
            $journal_ratings[]                    = $journal->rating;
            $journal_ratings_colors[]             = $journal->ratingColor();
            $journal_achievements[]               = $journal->achievements->count();
            $journal_achievements_colors[]        = '#40e0d0';

            $total_achievements                   += $journal->achievements->count();

            $journal_highest_word_counts[]        = strlen($journal->highest_point);
            $journal_highest_word_counts_colors[] = '#32d296';
            $journal_lowest_word_counts[]         = strlen($journal->lowest_point);
            $journal_lowest_word_counts_colors[]  = '#f0506e';

            // if we haven't got a journal rating, then we don't really need to append onto the star day in question.
            if ($journal->rating === null)
                continue;

            $star_days                            = "total_{$journal->rating}_star_days";
            $$star_days                           += 1;
        }

        // @todo - we are going to want to store this in a database row eventually, and then if the row doesn't exist
        // @todo - yet, then we are going to simply craft it from the month in question, and then dump it into a row...
        // @todo - this should save some resource values when loading this page up... this shouldn't ever get out of
        // @todo - hand however; but will be worth doing in the long run for whenever I decide I want to start logging
        // @todo - some other features.

        // Returning all of the above data, in a json-ised formatted data set so that javascript has an easier time
        // reading the data that we have returned. this is a build up of all the data above, and will be utilised in
        // reporting. (for the month in particular that has been passed)
        return response()->json([
            'journal_ratings'                    => $journal_ratings,
            'journal_ratings_colors'             => $journal_ratings_colors,
            'journal_achievements'               => $journal_achievements,
            'journal_achievements_colors'        => $journal_achievements_colors,
            'journal_highest_word_counts'        => $journal_highest_word_counts,
            'journal_highest_word_counts_colors' => $journal_highest_word_counts_colors,
            'journal_lowest_word_counts'         => $journal_lowest_word_counts,
            'journal_lowest_word_counts_colors'  => $journal_lowest_word_counts_colors,
            'labels'                             => $journal_labels,
            'total_achievements'                 => $total_achievements,
            'total_1_star_days'                  => $total_1_star_days,
            'total_2_star_days'                  => $total_2_star_days,
            'total_3_star_days'                  => $total_3_star_days,
            'total_4_star_days'                  => $total_4_star_days,
            'total_5_star_days'                  => $total_5_star_days,
            'date'                               => $date->format('Y-m'),
            'date_display'                       => $date->format('F Y')
        ]);
    }
}