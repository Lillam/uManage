<?php

namespace App\Jobs\Journal;

use Illuminate\Bus\Queueable;
use App\Models\Journal\Journal;
use Illuminate\Support\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;

class JournalLocalStoreJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
    * @var Collection
    */
    private Collection $years;

    /**
    * @var array
    */
    private array $put = [];

    /**
    * Create a new job instance.
    *
    * @return void
    */
    public function __construct()
    {
        $this->years = Journal::selectRaw('distinct substr(`when`, 1, 4) as `when`')->get()->map(
            fn ($journal) => $journal->getAttributes()['when']
        );
    }

    /**
    * Execute the job.
    *
    * @return void
    */
    public function handle()
    {
        // iterate on over each year that the system has for the journal, we're not going to want to completely
        // obliterate the file size with stuffing all the journals into a single json storage file.
        foreach ($this->years as $year_key => $year) {
            foreach ($this->getJournals($year) as $journal) {
                $this->put[$journal->id] = [
                    'id'            => $journal->id,
                    'user_id'       => $journal->user_id,
                    'rating'        => $journal->rating,
                    'overall'       => $journal->overall,
                    'lowest_point'  => $journal->lowest_point,
                    'highest_point' => $journal->highest_point,
                    'when'          => $journal->when,
                    'created_at'    => $journal->created_at,
                    'updated_at'    => $journal->updated_at
                ];

                // if the journal in question has some achievements associated to it then we are going to be iterating
                // over each one of these as well, and latching them into the respective put container; so that we are
                // going to be able to store the entirety of the database into a json oriented file for extraction
                // and re-insertion at some other time.
                if ($journal->achievements->isNotEmpty()) {
                    foreach ($journal->achievements as $journal_achievement) {
                        $this->put[$journal->id]['journal_achievements'][$journal_achievement->id] = [
                            'id'         => $journal_achievement->id,
                            'journal_id' => $journal_achievement->journal_id,
                            'name'       => $journal_achievement->name,
                            'created_at' => $journal_achievement->created_at,
                            'updated_at' => $journal_achievement->updated_at
                        ];
                    }
                }
            }

            // after getting all of the results, turn the entire collection into a json object and store it into a file.
            // this will be stored inside the local storage; public/storage/journals/journals.json?
            Storage::disk('local')
                ->put("journals/journals_{$year}.json", json_encode($this->put));

            // reset the put back to nothing, so that when we re-iterate onto a new file, the put is back at square one
            // rather than appending more and more data to the particular set.
            $this->put = [];
        }
    }

    /**
    * This method is entirely private to this particular instance, this is for acquiring journal models from the
    * database, and allowing us to be able to iterate over them in a rather coordinated fashion.
    *
    * @param string $year
    * @return Collection
    */
    private function getJournals(string $year): Collection
    {
        return Journal::with('achievements')
            ->where('when', 'like', "%{$year}%")
            ->get();
    }
}
