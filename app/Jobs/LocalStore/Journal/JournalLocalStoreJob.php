<?php

namespace App\Jobs\LocalStore\Journal;

use Illuminate\Bus\Queueable;
use App\Models\Journal\Journal;
use App\Jobs\LocalStore\Puttable;
use Illuminate\Support\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use App\Jobs\LocalStore\Destinationable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class JournalLocalStoreJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Destinationable, Puttable;

    /**
    * @var Collection
    */
    private Collection $years;

    /**
    * Create a new job instance.
    *
    * @return void
    */
    public function __construct(?string $destination = 'journals')
    {
        $this->setDestination($destination);

        $this->years = Journal::query()->selectRaw('distinct substr(`when`, 1, 4) as `when`')->get()->map(
            fn ($journal) => $journal->getAttributes()['when']
        );
    }

    /**
    * Execute the job.
    *
    * @return void
    */
    public function handle(): void
    {
        // iterate on over each year that the system has for the journal, we're not going to want to completely
        // obliterate the file size with stuffing all the journals into a single json storage file.
        foreach ($this->years as $year) {
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
                foreach ($journal->achievements as $journalAchievement) {
                    $this->put[$journal->id]['journal_achievements'][$journalAchievement->id] = [
                        'id'         => $journalAchievement->id,
                        'journal_id' => $journalAchievement->journal_id,
                        'name'       => $journalAchievement->name,
                        'created_at' => $journalAchievement->created_at,
                        'updated_at' => $journalAchievement->updated_at
                    ];
                }
            }

            // after getting all the results, turn the entire collection into a json object and store it into a file.
            // this will be stored inside the local storage; public/storage/journals/journals.json?
            Storage::disk('local')
                ->put($this->getDestination("journals_{$year}.json"), json_encode($this->put));

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
        return Journal::query()
            ->with('achievements')
            ->where('when', 'like', "%$year%")
            ->get();
    }
}
