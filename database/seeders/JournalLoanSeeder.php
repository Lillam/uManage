<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Journal\JournalLoan;
use Illuminate\Support\Facades\Storage;
use App\Models\Journal\JournalLoanPayback;

class JournalLoanSeeder extends Seeder
{
    /**
    * This method is reading from a local file that is stored in json memory so that we can extract this out
    * and use it to put the data back into the database, or simply carry these files anywhere and immediately
    * seed the database to the point the application was at previously.
    *
    * @return void
    */
    public function run(): void
    {
        if (! Storage::disk('local')->exists('journal_loans/journal_loans.json')) {
            return;
        }

        $journalLoans = collect(
            json_decode(Storage::disk('local')->get('journal_loans/journal_loans.json'))
        );

        DB::transaction(function () use ($journalLoans) {
            $bar = $this->command->getOutput()->createProgressBar($journalLoans->count());

            foreach ($journalLoans as $journalLoan) {
                JournalLoan::create([
                    'id'             => $journalLoan->id,
                    'user_id'        => $journalLoan->user_id,
                    'reference'      => $journalLoan->reference,
                    'amount'         => $journalLoan->amount,
                    'interest'       => $journalLoan->interest,
                    'when_loaned'    => $journalLoan->when_loaned,
                    'when_pay_back'  => $journalLoan->when_pay_back,
                    'when_paid_back' => $journalLoan->when_paid_back,
                    'paid_back'      => $journalLoan->paid_back
                ]);

                foreach ($journalLoan->paybacks as $payback) {
                    JournalLoanPayback::create([
                        'id'              => $payback->id,
                        'journal_loan_id' => $payback->journal_loan_id,
                        'user_id'         => $payback->user_id,
                        'amount'          => $payback->amount,
                        'paid_when'       => $payback->paid_when
                    ]);
                }

                $bar->advance();
            }

            $bar->finish();
        });

        DB::commit();
    }
}
