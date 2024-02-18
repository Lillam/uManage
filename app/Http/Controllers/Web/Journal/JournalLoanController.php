<?php

namespace App\Http\Controllers\Web\Journal;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use App\Models\Journal\JournalLoan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\Factory;
use App\Http\Controllers\Web\Controller;
use Illuminate\Contracts\Foundation\Application;

class JournalLoanController extends Controller
{
    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function _viewJournalLoansGet(Request $request): Application|Factory|View
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
        $user = $this->vs->get('user');

        $loans = JournalLoan::query()
            ->with('paybacks')
            ->where('user_id', '=', $user->id)
            ->get();

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
    public function _ajaxMakeJournalLoanPost(Request $request): void
    {
        JournalLoan::query()->create([
            'user_id'       => Auth::id(),
            'amount'        => $request->get('amount'),
            'interest'      => $request->get('interest'),
            'reference'     => $request->get('reference'),
            'when_loaned'   => Carbon::parse($request->get('when_loaned')),
            'when_pay_back' => Carbon::parse($request->get('when_pay_back'))
        ]);
    }
}
