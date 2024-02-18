<?php

namespace App\Http\Controllers\Web\Journal;

use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Journal\Journal;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\Factory;
use App\Helpers\DateTime\DateTimeHelper;
use App\Http\Controllers\Web\Controller;

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
        // let's check whether we have a date passed through the url parameters or not , if we do then we are going to
        // be parsing this as a date in the format of Year Month Day. otherwise, we are going to take today's date in
        // the format of Year Month Day.
        $date = DateTimeHelper::nowOrDate($request->input('date'));

        $this->vs->set('title', "Journal Report - {$this->vs->get('user')->getFullName()}")
            ->set('currentPage', 'page.journals.report');

        return view('journal.journal_report.view_journal_report', compact(
            'date'
        ));
    }

    /**
    * This method is entirely for grabbing journals in report format, this will be returning all the data in a json
    * oriented object so that the frontend can process what is going on here; all values will be viewed inside the
    * view_journals_report.js file, after ajax-ing has happened. This method is entirely a cycle, and for as many times
    * as the user will keep clicking next or back, will depend on the values that get returned (based on the past
    * date)
    *
    * @param Request $request
    * @return JsonResponse
    */
    public function _ajaxViewJournalsReportGet(Request $request): JsonResponse
    {
        // Acquire the date that the report is going to be generated off, the user will be able to cycle through the
        // months. And in doing so, providing there is a month set, we will be utilising the current month, otherwise
        // will be defaulted to today. if a direction has been passed in, then the DateTimeHelper is going to return a
        // date that from the date provided will be the date - 1  month or + 1 month depending on left or right
        // directional values.
        $date = DateTimeHelper::moveDateByMonths(
            Carbon::parse($request->input('date')),
            $request->input('direction')
        );

        // acquire all the journals that have a when between the start and end of the month for the month of which has
        // been designated above.
        $journals = Journal::query()
            ->where('user_id', '=', Auth::id())
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

        foreach ($journals as $journal) {
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
            if ($journal->rating === null) continue;

            $star_days                            = "total_{$journal->rating}_star_days";
            $$star_days                           += 1;
        }

        // @todo - we are going to want to store this in a database row eventually, and then if the row doesn't exist
        // @todo - yet, then we are going to simply craft it from the month in question, and then dump it into a row...
        // @todo - this should save some resource values when loading this page up... this shouldn't ever get out of
        // @todo - hand however; but will be worth doing in the long run for whenever I decide I want to start logging
        // @todo - some other features.

        // Returning all of the above data, in a json-ified formatted data set so that javascript has an easier time
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
