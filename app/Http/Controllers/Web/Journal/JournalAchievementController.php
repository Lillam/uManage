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
        $journalId = $request->input('journal_id');
        $journalAchievements = JournalAchievement::query()
            ->where('journal_id', '=', $journalId)
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
        $journalId = $request->input('journal_id');
        $journalAchievement = $request->input('journal_achievement');

        JournalAchievement::query()
            ->create([
                'name' => $journalAchievement,
                'journal_id' => $journalId
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
        $journalAchievementId = $request->input('journal_achievement_id');
        $field = $request->input('field');
        $value = $request->input('value');

        JournalAchievement::query()
            ->where('id', '=', $journalAchievementId)
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
        $journalId = $request->input('journal_id');
        $journalAchievementId = $request->input('journal_achievement_id');

        JournalAchievement::query()
            ->where('journal_id', '=', $journalId)
            ->where('id', '=', $journalAchievementId)
            ->delete();

        return response()->json([
            'response' => 'Successfully deleted achievement from this journal'
        ]);
    }
}
