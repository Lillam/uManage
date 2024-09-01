<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use App\Models\Journal\JournalLoan;
use Illuminate\Support\Facades\Auth;

class MakeJournalLoanRequest extends HandleRequest
{
    public function handle(): bool
    {
        JournalLoan::query()->create([
            'user_id'       => Auth::id(),
            'amount'        => $this->request->get('amount'),
            'interest'      => $this->request->get('interest'),
            'reference'     => $this->request->get('reference'),
            'when_loaned'   => Carbon::parse($this->request->get('when_loaned')),
            'when_pay_back' => Carbon::parse($this->request->get('when_pay_back'))
        ]);

        return true;
    }
}
