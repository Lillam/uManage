<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\Journal\JournalFinance;
use Illuminate\Support\Facades\Storage;

class JournalFinanceSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run(): void
    {
        $journal_finances = explode(
            "\n",
            Storage::disk('local')->get('journal_finances/journal_finances.csv')
        );

        foreach ($journal_finances as $journal_finance) {
            [$date, $cost, $current_balance, $currency, $where] = explode(',', $journal_finance);
            JournalFinance::query()->create([
                'user_id' => 122,
                'spend'   => $cost,
                'when'    => Carbon::parse($date)->format('Y-m-d'),
                'where'   => $where
            ]);
        }
    }
}
