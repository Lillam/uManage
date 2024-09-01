<?php

namespace App\Http\Controllers\Web\Journal;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use App\Models\Journal\JournalLoan;
use Illuminate\Contracts\View\Factory;
use App\Http\Controllers\Web\Controller;
use App\Http\Requests\MakeJournalLoanRequest;
use Illuminate\Contracts\Foundation\Application;

class JournalLoanController extends Controller
{
    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function _viewJournalLoansGet(): Application|Factory|View
    {
        $this->vs->set('title', "Loans {$this->vs->get('user')->getFullName()}")
                 ->set('currentPage', 'page.journals.loans');

        return view('journal.journal_loan.view_journal_loans');
    }

    /**
     * @return JsonResponse
     */
    public function _ajaxViewJournalLoansGet(): JsonResponse
    {
        $loans = JournalLoan::query()
            ->with('paybacks')
            ->where('user_id', '=', $this->vs->get('user')->id)
            ->get()
            ->filter(function ($loan) {
                $amount = $loan->amount + $loan->interest;

                foreach ($loan->paybacks as $payback) {
                    $amount -= $payback->amount;
                }

                return $amount > 0;
            });

        return response()->json([
            'html' => view('library.journal.journal_loan.ajax_view_journal_loans', compact(
                'loans'
            ))->render(),
        ]);
    }

    /**
     * @param Request $request
     * @return void
     */
    public function _ajaxMakeJournalLoanPost(MakeJournalLoanRequest $handler): void
    {
        $handler->handle();
    }
}
