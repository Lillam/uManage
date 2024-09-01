<?php

namespace App\Http\Requests;

use App\Models\Journal\Journal;

class DeleteJournalRequest extends HandleRequest
{
    public function handle(): bool
    {
        Journal::query()
            ->where('id', '=', $this->request->input('journal_id'))
            ->where('user_id', '=', $this->getUserId())
            ->delete();

        return true;
    }
}
