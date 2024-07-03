<?php

namespace App\Http\Controllers\Web\Journal;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Journal\JournalDiet;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\Factory;
use App\Http\Controllers\Web\Controller;
use App\Helpers\DateTime\DateTimeHelper;
use Illuminate\Contracts\Foundation\Application;

class JournalDietController extends Controller
{
    public function _viewJournalDietsGet(Request $request): Application|Factory|View
    {
        $date = DateTimeHelper::nowOrDate($request->input('date'));

        $this->vs->set('title', "Diet Journal - {$this->vs->get('user')->getFullName()}")
                 ->set('hasTitle', true)
                 ->set('currentPage', 'page.journals.diet.calendar');

        return view('journal.journal_diet.view_journal_diets', compact('date'));
    }

    public function _ajaxViewJournalDietsGet(Request $request): JsonResponse
    {
        $date = Carbon::parse($request->input('date'));

        $direction = $request->input('direction');

        if ($direction === 'left') {
            $date->subMonth();
        }

        if ($direction === 'right') {
            $date->addMonth();
        }

        $startOfMonth = $date->startOfMonth();

        $daysInMonth = $startOfMonth->daysInMonth;

        $startingDay = $startOfMonth->format('l');

        $days = Carbon::getDays();

        $dates = [];

        $dayKey = array_search($startingDay, $days);

        $journalDiets = JournalDiet::where('when', '>=', $startOfMonth->format('Y-m-d'))
            ->where('user_id', '=', Auth::id())
            ->where('when', '<=', $startOfMonth->endOfMonth()->format('Y-m-d'))
            ->get()
            ->keyBy(fn (JournalDiet $diet) => $diet->when->format('Y-m-d'));

        for ($dayIncrement = 1; $dayIncrement < ($daysInMonth + 1); ++ $dayIncrement) {
            $journalDietKey = "{$date->format('Y-m')}-" . ($dayIncrement < 10 ? '0' : '') . $dayIncrement;

            $dayKey = $dayKey === 7 ? 0 : $dayKey;

            $referenceDay = $days[$dayKey];

            $dates[$journalDietKey] = (object) [
                'title' => "$referenceDay <span class='uk-text-small'>{$date->format('M')} $dayIncrement</span>",
                'journalDiet' => $journalDiets->get($journalDietKey),
            ];

            if ($journalDiets->get($journalDietKey) instanceof JournalDiet) {
                $journalDiets->forget($journalDietKey);
            }

            $dayKey += 1;
        }

        return response()->json([
            'date' => $date->format('Y-m'),
            'date_display' => $date->format('F Y'),
            'html' => view('library.journal.journal_diet.ajax_view_journal_diets', compact('dates'))->render()
        ]);
    }
}
