<?php

namespace App\Http\Controllers\Journal;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\Factory;
use App\Models\Journal\JournalAchievement;
use App\Http\Controllers\Controller;

class JournalAchievementController extends Controller
{
    /**
    * @param Request $request
    * @return Factory|View
    */
    public function _ajaxViewJournalAchievementsGet(Request $request): Factory|View
    {
        $journal_id = $request->input('journal_id');
        $journal_achievements = JournalAchievement::where('journal_id', '=', $journal_id)
            ->get();

        return view('journal_achievement.ajax_view_journal_achievements', compact(
            'journal_achievements'
        ));
    }

    /**
    * @param Request $request
    * @return JsonResponse
    */
    public function _ajaxMakeJournalAchievementPost(Request $request): JsonResponse
    {
        $journal_id = $request->input('journal_id');
        $journal_achievement = $request->input('journal_achievement');

        JournalAchievement::create([
            'name' => $journal_achievement,
            'journal_id' => $journal_id
        ]);

        return response()->json([
            'response' => 'Successfully added achivement'
        ]);
    }

    /**
    * @param Request $request
    * @return JsonResponse
    */
    public function _ajaxEditJournalAchievementPost(Request $request): JsonResponse
    {
        $journal_achievement_id = $request->input('journal_achievement_id');
        $field = $request->input('field');
        $value = $request->input('value');

        JournalAchievement::where('id', '=', $journal_achievement_id)
            ->update([
                $field => $value
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
        $journal_id = $request->input('journal_id');
        $journal_achievement_id = $request->input('journal_achievement_id');

        JournalAchievement::where('journal_id', '=', $journal_id)
            ->where('id', '=', $journal_achievement_id)->delete();

        return response()->json([
            'response' => 'Successfully deleted achievement from this journal'
        ]);
    }
}
