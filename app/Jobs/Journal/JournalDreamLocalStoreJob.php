<?php

namespace App\Jobs\Journal;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Collection;
use App\Models\Journal\JournalDream;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class JournalDreamLocalStoreJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private array $put = [];

    /**
    * Create a new Job Instance.
    *
    * JournalDreamLocalStoreJob constructor.
    *
    * @return void
    */
    public function __construct()
    {

    }

    /**
    * Execute the job.
    *
    * @return void
    */
    public function handle()
    {
        foreach ($this->getDreamJournals() as $dream_journal) {
            $this->put[$dream_journal->id] = [
                'id'         => $dream_journal->id,
                'user_id'    => $dream_journal->user_id,
                'rating'     => $dream_journal->rating,
                'content'    => $dream_journal->content,
                'meaning'    => $dream_journal->meaning,
                'when'       => $dream_journal->when,
                'created_at' => $dream_journal->created_at,
                'updated_at' => $dream_journal->updated_at
            ];
        }

        // after getting all the results, turn the entire collection into a json object and store it into a file.
        // this will be stored inside the local storage; public/storage/journal_dreams/journal_dreams.json
        Storage::disk('local')
            ->put("journal_dreams/journal_dreams.json", json_encode($this->put));
    }

    /**
    * @return Collection
    */
    public function getDreamJournals(): Collection
    {
        return JournalDream::all();
    }
}