<?php

namespace App\Jobs\LocalStore\Journal;

use Illuminate\Support\Collection;
use App\Models\Journal\JournalLoan;
use App\Jobs\LocalStore\LocalStoreJob;
use App\Models\Journal\JournalLoanPayback;

class JournalLoanLocalStoreJob extends LocalStoreJob
{
    public function __construct(?string $destination = 'journal_loans')
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
        foreach ($this->getJournalLoans() as $journalLoan) {
            $this->put[$journalLoan->id] = [
                'id'             => $journalLoan->id,
                'user_id'        => $journalLoan->user_id,
                'reference'      => $journalLoan->reference,
                'amount'         => $journalLoan->amount,
                'interest'       => $journalLoan->interest,
                'when_loaned'    => $journalLoan->when_loaned,
                'when_pay_back'  => $journalLoan->when_pay_back,
                'when_paid_back' => $journalLoan->when_paid_back,
                'paid_back'      => $journalLoan->paid_back,
                'paybacks'       => [],
            ];

            foreach ($journalLoan->paybacks as $payback) {
                $this->put[$journalLoan->id]['paybacks'][$payback->id] = [
                    'id'              => $payback->id,
                    'journal_loan_id' => $payback->journal_loan_id,
                    'user_id'         => $payback->user_id,
                    'amount'          => $payback->amount,
                    'paid_when'       => $payback->paid_when
                ];
            }
        }

        $this->putToStorage('local', 'journal_loans.json');
    }

    /**
    * Get all the journal loan paybacks.
    *
    * @return Collection
    */
    private function getJournalLoans(): Collection
    {
        return JournalLoan::query()->with('paybacks')->get();
    }
}
