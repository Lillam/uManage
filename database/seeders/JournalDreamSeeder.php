<?php

namespace Database\Seeders;

use Parsedown;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Journal\JournalDream;
use Illuminate\Support\Facades\Storage;

class JournalDreamSeeder extends Seeder
{
    public function run(): void
    {
        $journal_dreams = collect(
            json_decode(Storage::disk('local')->get('journal_dreams/journal_dreams.json'))
        );

        DB::transaction(function () use ($journal_dreams) {
            $bar = $this->command->getOutput()->createProgressBar($journal_dreams->count());
            foreach ($journal_dreams as $journal_dream) {
                $parsedown = (new Parsedown())->setSafeMode(true);
                JournalDream::updateOrCreate(['id' => $journal_dream->id], [
                    'id'      => $journal_dream->id,
                    'user_id' => 1,
                    'rating'  => $journal_dream->rating,
                    'content' => $parsedown->parse($journal_dream->content),
                    'meaning' => $parsedown->parse($journal_dream->meaning),
                    'when'    => $journal_dream->when,
                ]); $bar->advance();
            } $bar->finish();
        }); DB::commit();
    }
}