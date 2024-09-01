<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use App\Models\Journal\JournalLoanPayback;

class MakeJournalLoanPaybackRequest extends HandleRequest
{
    public function handle(): bool
    {
        [
            'journal_loan_id' => $journal_loan_id,
            'amount'          => $amount,
            'when'            => $when
        ] = $this->request->only('journal_loan_id', 'amount', 'when');

        JournalLoanPayback::query()->create([
            'user_id'         => $this->getUserId(),
            'journal_loan_id' => $journal_loan_id,
            'amount'          => $amount,
            'paid_when'       => Carbon::parse($when)
        ]);

        return true;
    }
}
