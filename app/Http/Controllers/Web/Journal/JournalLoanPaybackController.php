<?php

namespace App\Http\Controllers\Web\Journal;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Web\Controller;
use App\Http\Requests\MakeJournalLoanPaybackRequest;
use App\Models\Journal\JournalLoanPayback;

class JournalLoanPaybackController extends Controller
{
    public function _ajaxViewJournalLoanPaybacksGet(Request $request): JsonResponse
    {
        $user = $this->vs->get('user');

        $paybacks = JournalLoanPayback::query()
            ->where('user_id', '=', $user->id)
            ->where('journal_loan_id', '=', $request->get('journal_loan_id'))
            ->get();

        return response()->json([
            'html' => view('library.journal.journal_loan.ajax_view_journal_loan_paybacks', compact(
                'paybacks'
            ))->render()
        ]);
    }

    public function _ajaxMakeJournalLoanPaybackPost(MakeJournalLoanPaybackRequest $handler): void
    {
        $handler->handle();
    }
}
