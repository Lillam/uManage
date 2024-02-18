<?php

namespace App\Http\Controllers\Web\Journal;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\Factory;
use App\Http\Controllers\Web\Controller;
use App\Models\Journal\JournalAchievement;

class JournalAchievementController extends Controller
{
    /**
    * @param Request $request
    * @return Factory|View
    */
    public function _ajaxViewJournalAchievementsGet(Request $request): Factory|View
    {
        $journalAchievements = JournalAchievement::query()
            ->where('journal_id', '=', $request->input('journal_id'))
            ->get();

        return view('journal_achievement.ajax_view_journal_achievements', compact(
            'journalAchievements'
        ));
    }

    /**
    * @param Request $request
    * @return JsonResponse
    */
    public function _ajaxMakeJournalAchievementPost(Request $request): JsonResponse
    {
        JournalAchievement::query()->create([
            'name' => $request->input('journal_achievement'),
            'journal_id' => $request->input('journal_id')
        ]);

        return response()->json([
            'response' => 'Successfully added achievement'
        ]);
    }

    /**
    * @param Request $request
    * @return JsonResponse
    */
    public function _ajaxEditJournalAchievementPost(Request $request): JsonResponse
    {
        JournalAchievement::query()
            ->where('id', '=', $request->input('journal_achievement_id'))
            ->update([
                $request->input('field') => $request->input('value')
            ]);

        return response()->json([
            'response' => 'This has successfully been updated'
        ]);
    }

    /**
    * @param Request $request
    * @return JsonResponse
    */
    public function _ajaxDeleteJournalAchievementPost(Request $request): JsonResponse
    {
        JournalAchievement::query()
            ->where('journal_id', '=', $request->input('journal_id'))
            ->where('id', '=', $request->input('journal_achievement_id'))
            ->delete();

        return response()->json([
            'response' => 'Successfully deleted achievement from this journal'
        ]);
    }
}
