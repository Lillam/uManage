@extends('template.master')
@section('css')
    {!! ($vs->css)('views/journal_finance/view_journal_finances') !!}
@endsection
@section('js')
    {!! ($vs->js)('views/journal_finance/view_journal_finances') !!}
@endsection
@section('body')
    {{-- Journal Navigation --}}
    <div class="journal_finance_navigation navigation">
        <div class="uk-flex">
            <div class="uk-width-expand uk-flex uk-flex-middle">
                <span class="journal_finance_date">{{ $date->format('F Y') }}</span>
            </div>
            <div class="uk-width-auto uk-border-left">
                <a class="journal_finance_calendar_left uk-button uk-icon-button fa fa-arrow-left"></a>
            </div>
            <div class="uk-width-auto uk-text-right uk-border-left">
                <a class="journal_finance_calendar_right uk-button uk-icon-button fa fa-arrow-right"></a>
            </div>
        </div>
    </div>
    {{--<div class="progress journal_progress"><div class="progress_percent" style="width: 0%;"></div></div>--}}
    {{-- Journals Container --}}
    {{-- Ajax View Journals --}}
    <div id="journal_finances"
         data-view_journal_finances_url="{{ action('Journal\JournalFinanceController@_ajaxViewJournalFinancesGet') }}"
         data-date="{{ $date->format('Y-m') }}">
    </div>
@endsection