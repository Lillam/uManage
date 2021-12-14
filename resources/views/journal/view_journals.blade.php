@extends('template.master')
@section('css')
    {!! ($vs->css)('views/journals/view_journals') !!}
@endsection
@section('js')
    {!! ($vs->js)('views/journals/view_journals') !!}
@endsection
@section('body')
    {{-- Journal Navigation --}}
    <div class="journal_navigation navigation">
        <div class="uk-flex">
            <div class="uk-width-expand uk-flex uk-flex-middle">
                <span class="journal_date">{{ $date->format('F Y') }}</span>
            </div>
            <div class="uk-width-auto uk-border-left">
                <a class="journal_calendar_left uk-button uk-icon-button fa fa-arrow-left"></a>
            </div>
            <div class="uk-width-auto uk-text-right uk-border-left">
                <a class="journal_calendar_right uk-button uk-icon-button fa fa-arrow-right"></a>
            </div>
            <div class="uk-width-auto uk-border-left">
                <a class="uk-button uk-icon-button"
                   href="{{ action('Journal\JournalReportController@_viewJournalsReportGet') }}"><i class="fa fa-chart-pie"></i></a>
            </div>
        </div>
    </div>
    <div class="progress journal_progress"><div class="progress_percent" style="width: 0%;"></div></div>
    {{-- Journals Container --}}
    {{-- Ajax View Journals --}}
    <div id="journals"
         data-view_journals_url="{{ action('Journal\JournalController@_ajaxViewJournalsGet') }}"
         data-date="{{ $date->format('Y-m') }}">
    </div>
@endsection