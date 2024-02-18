<?php

namespace Database\Seeders;

use Parsedown;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Journal\JournalDream;
use Illuminate\Support\Facades\Storage;

class JournalDreamSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run(): void
    {
        $journalDreams = collect(
            json_decode(Storage::disk('local')->get('journal_dreams/journal_dreams.json'))
        );

        DB::transaction(function () use ($journalDreams) {
            $bar = $this->command->getOutput()->createProgressBar($journalDreams->count());

            foreach ($journalDreams as $journalDream) {
                $parsedown = (new Parsedown())->setSafeMode(true);

                JournalDream::query()->updateOrCreate(['id' => $journalDream->id], [
                    'id'      => $journalDream->id,
                    'user_id' => 1,
                    'rating'  => $journalDream->rating,
                    'content' => $parsedown->parse($journalDream->content),
                    'meaning' => $parsedown->parse($journalDream->meaning),
                    'when'    => $journalDream->when,
                ]);

                $bar->advance();
            }

            $bar->finish();
        });

        DB::commit();
    }
}
