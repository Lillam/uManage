<?php

namespace App\Http\Controllers\Web\Journal;

use DateTime;
use Illuminate\Http\RedirectResponse;
use Throwable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Journal\Journal;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\Factory;
use App\Helpers\DateTime\DateTimeHelper;
use App\Http\Controllers\Web\Controller;
use Illuminate\Contracts\Foundation\Application;

class JournalController extends Controller
{
    /**
    * This method is the hub handler for the journals landing page, when the page loads, we are going to want to take
    * a snapshot of what the current date they're looking at, and assign this to a variable, if this doesn't exist then
    * we just want to create the date for today (Carbon::now()) and then assign this to an attribute on the view itself
    * we will then utilise this view date to what will be passed to the ajax method (ajaxViewJournalsGet()).
    *
    * @param Request $request
    * @return Application|Factory|View
    */
    public function _viewJournalsGet(Request $request): Application|Factory|View
    {
//        $journals = Journal::query()->orWhere('lowest_point', 'LIKE', '%gril%')
//                                    ->orWhere('highest_point', 'LIKE', '%gril%')
//                                    ->orWhere('overall', 'LIKE', '%gril%')
//                                    ->orderBy('when')
//                                    ->paginate(1);
//
//        $target = $journals->items()[0];
//
//        echo "<h1>{$target->when}</h1>";
//        echo "<br />";
//        echo $target->overall;
//        echo "<br />";
//        echo $target->lowest_point;
//        echo "<br />";
//        echo $target->highest_point;
//        die();

        $date = DateTimeHelper::nowOrDate($request->input('date'));

        $this->vs->set('title', "Journals - {$this->vs->get('user')->getFullName()}")
                 ->set('currentPage', 'page.journals.calendar');

        return view('journal.view_journals', compact(
            'date'
        ));
    }

    /**
    * @param Request $request
    * @return JsonResponse
    * @throws Throwable
    */
    public function _ajaxViewJournalsGet(Request $request): JsonResponse
    {
        // the date, which will be by default today (coming from the view page) and when the ajax call is hit here,
        // this will be coming either with a passed date, or the date that is today.
        $date = Carbon::parse($request->input('date'));

        // direction by default will either be a blank string, left or right. if direction isn't set then  this will
        // simply be ignored.
        $direction = $request->input('direction');

        // if the direction is set, and is designated to be going left, then we are looking to cycle through dates into
        // the past and grabbing all journals that are beyond today backwards.
        if ($direction === 'left')
            $date->subMonth();

        // if the direction is set and designated to be going right, then we are looking to cycle through dates into the
        // future and grabbing all journals that are beyond today, forwards.
        if ($direction === 'right')
            $date->addMonth();

        // grab the start of the month from the date that we have passed, so that we can calculate how many days are in
        // this particular month from the very beginning.
        $start_of_month = $date->startOfMonth();

        // grab the total amount of days that are in this particular month from the date object, from the start of the
        // month.
        $daysInMonth = $start_of_month->daysInMonth;

        // acquire the starting day in the format of text (Saturday, Sunday) etc.
        $starting_day = $start_of_month->format('l');

        // get the days from Sunday - Saturday. (Sunday, Monday, Tuesday, Wednesday...)
        $days = Carbon::getDays();

        // initiate the dates array that we will be passing through  to the blade file.
        $dates = [];

        // get the starting day key that we can increment from and apply to the keys of each journal day.
        $day_key = array_search($starting_day, $days);

        // acquire all the journals, that are from the start of the month, to the end of the month.
        $journals = Journal::query()
            ->with(['achievements'])
            ->where('user_id', '=', Auth::id())
            ->where('when', '>=', Carbon::parse($date)->startOfMonth()->format('Y-m-d'))
            ->where('when', '<=', Carbon::parse($date)->endOfMonth()->format('Y-m-d'))
            ->get()
            ->keyBy(fn (Journal $journal) => $journal->when->format('Y-m-d'));

        $journal_count = $journals->count();

        for ($dayIncrement = 1; $dayIncrement < ($daysInMonth + 1); ++ $dayIncrement) {
            // getting our journal key...
            $journal_key = "{$date->format('Y-m')}-" . ($dayIncrement < 10 ? '0' : '') . "$dayIncrement";

            // if the day key is 7, then we have reached the end and should be set back to the beginning. we don't have a
            // 7 value in the day key... thus we are needing a reset.
            $day_key = $day_key === 7 ? 0 : $day_key;

            $reference_day = $days[$day_key];

            $dates[$journal_key] = (object) [
                'title' => "$reference_day <span class='uk-text-small'>{$date->format('M')} $dayIncrement</span>",
                'journal' => ! empty($journals->get($journal_key)) ? $journals->get($journal_key) : null
            ];

            // when we have finished iterating over this particular item, unset it. as we have just turned this particular
            // entry into an object.
            if ($journals->get($journal_key) instanceof Journal)
                $journals->forget($journal_key);

            // increment the day key, so that we are able to gather the next day in the iterable instance.
            $day_key += 1;
        }

        $journal_percentage = ceil(round(($journal_count / ($dayIncrement - 1)) * 100));

        return response()->json([
            'date'               => $date->format('Y-m'),
            'date_display'       => $date->format('F Y'),
            'html'               => view('library.journal.ajax_view_journals', compact(
                'dates'
            ))->render(),
            'journal_percentage' => $journal_percentage
        ]);
    }

    /**
    * This method will simply be the handler for when we are going to the /journal/{date_string} path; when this
    * happens we are just going to display the first journal that we get for this url, and if it doesn't exist, we are
    * just going to create the journal entry...
    *
    * @param string $date
    * @return RedirectResponse|Application|Factory|View
    */
    public function _viewJournalGet(string $date): RedirectResponse|Application|Factory|View
    {
        // if the date in which the user has tried to access does not yet equate to today, then we're going to kick the
        // user back to the page prior to this, as we're not going to want to create the particular date yet in the
        // database.
        if (DateTimeHelper::greaterThanNow($date)) {
            return redirect()->route('journals.calendar');
        }

        $user    = $this->vs->get('user');
        $userId  = $user->id;
        $journal = Journal::query()
            ->where('when', '=', $date)
            ->where('user_id', '=', $userId)
            ->first();

        // Acquire the journal page from yesterday, this will be taking the data that is passed, and then making a new
        // date and reducing a day.
        $yesterday_link = action(
            [self::class, '_viewJournalGet'],
            Carbon::parse($date)->subDay()->format('Y-m-d')
        );

        // Acquire the journal page for tomorrow (this will be taking the data that is passed, and then making a new
        // date, and adding a day.
        $tomorrow_link  = action(
            [self::class, '_viewJournalGet'],
            Carbon::parse($date)->addDay()->format('Y-m-d')
        );

        // if we do have a journal entry, then... we are quite possibly going to need to make it so that we can
        // reference it on the frontend. as the id of the journal will be required for the achievements concept.
        if (! $journal instanceof Journal)
            $journal = Journal::query()->create(['user_id' => $userId, 'when' => $date]);

        $this->vs->set('title', "Journal - {$user->getFullName()}'s $date")
            ->set('currentPage',
                (new DateTime)->format('Y-m-d') === $journal->when->format('Y-m-d')
                    ? 'page.journals.journal.today'
                    : 'page.journals.journal'
            );

        return view('journal.view_journal', compact(
            'date',
            'journal',
            'yesterday_link',
            'tomorrow_link'
        ));
    }

    /**
    * This method is entirely for updating the journals; this will allow us to dynamically edit any part of a journal
    * entry; simply by passing in a field and a value; we can dynamically access the field by $model->$field = $value;
    *
    * @param Request $request
    * @return JsonResponse
    */
    public function _ajaxEditJournalPost(Request $request): JsonResponse
    {
        $journalId = $request->input('journal_id');

        ['field' => $field, 'value' => $value] = $request->only('field', 'value');

        $journal = Journal::query()
            ->where('id', '=', $journalId)
            ->first();

        $journal->$field = $value;
        $journal->save();

        return response()->json([
            'response' => "successfully updated the journal's $field value"
        ]);
    }

    /**
    * This method is specifically for deleting the journal entry in question, if the user has clicked on a date, (a
    * journal entry will have been created from that, however if this was unintentional and they don't want the entry
    * then they are going to need a means of deletion... this will control the deletion).
    * when the deletion has gone through successfully, the user will be returned a redirection link which will be
    * processed via the javascript.
    *
    * @param Request $request
    * @return JsonResponse
    */
    public function _ajaxDeleteJournalPost(Request $request): JsonResponse
    {
        Journal::query()
            ->where('id', '=', $request->input('journal_id'))
            ->where('user_id', '=', Auth::id())
            ->delete();

        return response()->json([
            'response' => route('journals.calendar'),
        ]);
    }
}
