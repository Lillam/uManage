<?php

namespace App\Http\Requests;

use App\Models\Journal\JournalAchievement;

class MakeJournalAchievementRequest extends HandleRequest
{
    public function handle(): bool
    {
        ['journal_achievement' => $name, 'journal_id' => $journal_id] = $this->request->only(
            'journal_achievement', 'journal_id'
        );

        JournalAchievement::query()->create([
            'name'       => $name,
            'journal_id' => $journal_id
        ]);

        return true;
    }
}
