<?php

namespace App\Jobs\LocalStore\Journal;

use App\Models\Journal\JournalDream;
use App\Jobs\LocalStore\LocalStoreJob;

class JournalDreamLocalStoreJob extends LocalStoreJob
{
    /**
    * Create a new Job Instance.
    *
    * JournalDreamLocalStoreJob constructor.
    *
    * @param string|null $destination
    * @return void
    */
    public function __construct(?string $destination = 'journal_dreams/journal_dreams.json')
    {
        $this->setDestination($destination);
    }

    /**
    * Execute the job.
    *
    * @return void
    */
    public function handle(): void
    {
        foreach (JournalDream::all() as $dreamJournal) {
            $this->put[$dreamJournal->id] = [
                'id'         => $dreamJournal->id,
                'user_id'    => $dreamJournal->user_id,
                'rating'     => $dreamJournal->rating,
                'content'    => $dreamJournal->content,
                'meaning'    => $dreamJournal->meaning,
                'when'       => $dreamJournal->when,
                'created_at' => $dreamJournal->created_at,
                'updated_at' => $dreamJournal->updated_at
            ];
        }

        // after getting all the results, turn the entire collection into a json object and store it into a file.
        // this will be stored inside the local storage; public/storage/journal_dreams/journal_dreams.json
        $this->putToStorage('local');
    }
}