<?php

namespace App\Http\Controllers\Web\Journal;

use Throwable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use App\Models\Journal\JournalDream;
use Illuminate\Contracts\View\Factory;
use App\Http\Controllers\Web\Controller;
use Illuminate\Contracts\Foundation\Application;

class JournalDreamController extends Controller
{
    /**
    * @param Request $request
    * @return Factory|View
    */
    public function _viewJournalDreamsGet(Request $request): Factory|View
    {
        $date = Carbon::now();

        $this->vs->set('title', "Dream Journals - {$this->vs->get('user')->getFullName()}")
                 ->set('currentPage', 'page.journals.dreams.calendar');

        return view('journal.journal_Dream.view_journal_dreams', compact(
            'date'
        ));
    }

    /**
    * @param Request $request
    * @return JsonResponse
    * @throws Throwable
    */
    public function _ajaxViewJournalDreamsGet(Request $request): JsonResponse
    {
        $date = Carbon::parse($request->input('date'));

        if ($direction = $request->input('direction')) {
            $date = match($direction) {
                "left" => $date->subMonth(),
                "right" => $date->addMonth()
            };
        }

        $startOfMonth = Carbon::parse($date)->startOfMonth();

        $daysInMonth = $startOfMonth->daysInMonth;

        $startingDay = $startOfMonth->format('l');

        $days = Carbon::getDays();

        $dates = [];

        $dayKey = array_search($startingDay, $days);

        $journalDrams = JournalDream::query()
            ->select('*')
            ->where('user_id', '=', $this->vs->get('user')->id)
            ->where('when', '>=', Carbon::parse($date)->startOfMonth()->format('Y-m-d'))
            ->where('when', '<=', Carbon::parse($date)->endOfMonth()->format('Y-m-d'))
            ->get()
            ->keyBy(fn (JournalDream $journalDream) => $journalDream->when->format('Y-m-d'));

        for ($dayIncrement = 1; $dayIncrement < ($daysInMonth + 1); ++ $dayIncrement) {
            $journalDreamKey = "{$date->format('Y-m')}-" . ($dayIncrement < 10 ? '0' : '') . "$dayIncrement";

            if ($dayKey === 7)
                $dayKey = 0;

            $referenceDay = $days[$dayKey];

            $dates[$journalDreamKey] = (object) [
                'title' => "$referenceDay <span class='uk-text-small'>{$date->format('M')} $dayIncrement</span>",
                'journal_dream' => ! empty($journalDrams->get($journalDreamKey)) ? $journalDrams->get($journalDreamKey)
                                                                                 : null
            ];

            $dayKey += 1;
        }

        return response()->json([
            'date' => $date->format('Y-m'),
            'date_display' => $date->format('F Y'),
            'html' => view('library.journal.journal_dream.ajax_view_journal_dreams',
                compact('dates')
            )->render(),
        ]);
    }

    /**
    * @param Request $request
    * @param string $date
    * @return Application|Factory|View
    */
    public function _viewJournalDreamGet(Request $request, string $date): Application|Factory|View
    {
        $journal_dream = JournalDream::query()->where('when', '=', $date)
                                              ->first();

        if (! $journal_dream instanceof JournalDream)
            $journal_dream = JournalDream::query()
                ->create([ 'user_id' => $this->vs->get('user')->id, 'when' => $date ]);

        // Acquire the journal dream page from yesterday, this will be taking the data that is passed, and then making a
        // new date and reducing a day.
        $yesterday_link = route('journals.dreams.dream', Carbon::parse($date)->subDay()->format('Y-m-d'));

        // Acquire the journal dream page for tomorrow (this will be taking the data that is passed, and then making a
        // new date, and adding a day.
        $tomorrow_link  = route('journals.dreams.dream', Carbon::parse($date)->addDay()->format('Y-m-d'));

        $title = 'Dream Journal | '
            . $this->vs->get('user')->getFullName() . ' | '
            . $journal_dream->when->format('jS F Y');

        $this->vs->set('title', $title)
                 ->set('currentPage', 'page.journals.dreams');

        return view('journal.journal_dream.view_journal_dream', compact(
            'journal_dream',
            'yesterday_link',
            'tomorrow_link'
        ));
    }

    /**
    * @param Request $request
    * @return JsonResponse
    */
    public function _editJournalDreamPost(Request $request): JsonResponse
    {
        $journal_dream_id = $request->input('journal_dream_id');

        $field = $request->input('field');
        $value = $request->input('value');

        $journalDream = JournalDream::query()->where('id', '=', $journal_dream_id)
                                             ->first();

        $journalDream->$field = $value;
        $journalDream->save();

        return response()->json([ 'response' => 'successfully updated the field' ]);
    }

    /**
    * This method is the handler for deleting a dream journal entry in the database, if the user has clicked on a
    * date that they don't really want then they have the ability to delete it, this is going to be handled via
    * ajax; and then return a route that the system will redirect to (Which will be the dream journals landing page).
    *
    * @param Request $request
    * @return JsonResponse
    */
    public function _ajaxDeleteJournalDreamPost(Request $request): JsonResponse
    {
        JournalDream::query()->where('id', '=', $request->input('journal_dream_id'))
                             ->where('user_id', '=', $this->vs->get('user')->id)
                             ->delete();

        return response()->json([ 'response' => action('Journal\JournalDreamController@_viewJournalDreamsGet') ]);
    }
}
