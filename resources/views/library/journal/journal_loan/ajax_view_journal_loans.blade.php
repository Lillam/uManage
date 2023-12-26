@foreach ($loans as $loan)
    <div class="loan" data-loan-id="{{ $loan->id }}">
        <div>
            <h2>#{{ $loan->id }} {{ $loan->reference }}</h2>
            <a class="uk-button uk-icon-button fa fa-plus" href="#add_journal_loan_payback_modal" uk-toggle></a>
        </div>
        <p>Amount owed: £{{ $loan->amount }}</p>
        <p>Amount owed With Tax: £{{ $loan->amount + $loan->interest }}</p>
        <p>Amount remaining: £{{ ($loan->amount + $loan->interest) - $loan->paybacks->sum('amount') }}</p>
        <h2>Paid Back:</h2>
        <div id="paybacks">
            @foreach($loan->paybacks as $payback)
                <p>[{{ $payback->paid_when->format('Y-m-d') }}] £{{ $payback->amount }}</p>
            @endforeach
        </div>
    </div>
@endforeach

<div id="add_journal_loan_payback_modal" uk-modal
     data-make_journal_loan_payback_url="{{ route('journals.finances.loan.paybacks.create.ajax') }}"
     data-view_journal_loan_paybacks_url="{{ route('journals.finances.loan.paybacks.view.ajax') }}">
    <div class="uk-modal-dialog uk-margin-auto-vertical">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-header">
            <h2 class="uk-modal-title">Add Repayment</h2>
        </div>
        <div class="uk-modal-body" uk-overflow-auto>
            <form class="uk-form uk-grid uk-grid-small" uk-grid>
                <div class="uk-width-1-1">
                    <label for="repayment_amount" class="uk-hidden">Amount</label>
                    <input type="text"
                           id="repayment_amount"
                           placeholder="Amount..."
                    />
                </div>
                <div class="uk-width-1-1">
                    <label for="repayment_when" class="uk-hidden">When</label>
                    <input type="text"
                           id="repayment_when"
                           placeholder="When..."
                    />
                </div>
            </form>
        </div>
        <div class="uk-modal-footer uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
            <button class="uk-button uk-button-primary make_journal_loan_payback" type="button">Save</button>
        </div>
    </div>
</div>