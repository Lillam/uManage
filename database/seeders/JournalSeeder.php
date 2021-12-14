<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Journal\Journal;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Journal\JournalAchievement;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class JournalSeeder extends Seeder
{
    /**
    * This method is 100% reading from a local file that constantly gets saved to whenever we are making amends or
    * creating new journal entries. this is for being able to retain the very important information, whilst retaining
    * the ability to fresh migrate the database.
    *
    * @throws FileNotFoundException
    * @return void
    */
    public function run()
    {
        foreach (Storage::disk('local')->allFiles('journals') as $file) {
            $journals = json_decode(Storage::disk('local')->get($file));

            DB::transaction(function () use ($journals) {
                $bar = $this->command->getOutput()->createProgressBar($journals->count());
                foreach ($journals as $journal_id => $journal) {
                    Journal::updateOrCreate(['id' => $journal_id], [
                        'id'            => $journal_id,
                        'user_id'       => $journal->user_id,
                        'rating'        => $journal->rating,
                        'overall'       => $journal->overall,
                        'lowest_point'  => $journal->lowest_point,
                        'highest_point' => $journal->highest_point,
                        'when'          => Carbon::parse($journal->when),
                        'created_at'    => Carbon::parse($journal->created_at),
                        'updated_at'    => Carbon::parse($journal->updated_at)
                    ]);

                    if (! empty($journal->journal_achievements)) {
                        foreach ($journal->journal_achievements as $journal_achievement_id => $journal_achievement) {
                            JournalAchievement::updateOrCreate(['id' => $journal_achievement_id], [
                                'id'         => $journal_achievement_id,
                                'journal_id' => $journal_id,
                                'name'       => $journal_achievement->name,
                                'created_at' => Carbon::parse($journal_achievement->created_at),
                                'updated_at' => Carbon::parse($journal_achievement->updated_at)
                            ]);
                        }
                    } $bar->advance();
                } $bar->finish();
            }); DB::commit();
        }
    }
}