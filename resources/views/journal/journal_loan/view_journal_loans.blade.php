@extends('template.master')
@section('css')
    {!! ($vs->css)('views/journal_loans/view_journal_loans') !!}
@endsection
@section('js')
    {!! ($vs->js)('views/journal_loans/view_journal_loans') !!}
@endsection
@section('sidebar')
    @include('library.journal.journals_sidebar')
@endsection
@section('title-block')
    <div class="uk-width-expand">
        <p>Loans</p>
    </div>
    <div class="uk-flex navigation">
        <div class="uk-width-auto">
            <a class="uk-button uk-icon-button fa fa-plus" href="#add_journal_loan_modal" uk-toggle></a>
        </div>
    </div>
@endsection
@section('body')
    <div class="loans simple-list"
         data-view_journal_loans_url="{{ route('journals.finances.loans.view.ajax') }}">
    </div>
    <div id="add_journal_loan_modal" uk-modal
         data-make_journal_loan_url="{{ route('journals.finances.loans.create.ajax') }}">
        <div class="uk-modal-dialog uk-margin-auto-vertical">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <div class="uk-modal-header">
                <h2 class="uk-modal-title">Add Loan</h2>
            </div>
            <div class="uk-modal-body" uk-overflow-auto>
                <form class="uk-form uk-grid uk-grid-small" uk-grid>
                    <div class="uk-width-1-1">
                        <label for="amount" class="uk-hidden">Amount</label>
                        <input type="text"
                               id="amount"
                               placeholder="Amount..."
                        />
                    </div>
                    <div class="uk-width-1-1">
                        <label for="reference" class="uk-hidden">Reference</label>
                        <input type="text"
                               id="reference"
                               placeholder="Reference..."
                        />
                    </div>
                    <div class="uk-width-1-1">
                        <label for="interest" class="uk-hidden">Interest</label>
                        <input type="text"
                               id="interest"
                               placeholder="Interest..."
                        />
                    </div>
                    <div class="uk-width-1-1">
                        <label for="when_loaned" class="uk-hidden">When Borrowing</label>
                        <input type="text"
                               id="when_loaned"
                               placeholder="When Borrowing..."
                        />
                    </div>
                    <div class="uk-width-1-1">
                        <label for="when_pay_back" class="uk-hidden">When Paying Back</label>
                        <input type="text"
                               id="when_pay_back"
                               placeholder="When Paying Back..."
                        />
                    </div>
                </form>
            </div>
            <div class="uk-modal-footer uk-text-right">
                <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
                <button class="uk-button uk-button-primary make_journal_loan" type="button">Save</button>
            </div>
        </div>
    </div>
@endsection