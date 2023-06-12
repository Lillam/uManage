@extends('template.master')
@section('css')
    {!! ($vs->css)('views/journals/view_journals') !!}
@endsection
@section('js')
    {!! ($vs->js)('views/journals/view_journals') !!}
@endsection
@section('sidebar')
    @include('library.journal.journals_sidebar')
@endsection
@section('title-block')
    <div class="uk-width-expand">
        <p><span class="journal_date">{{ $date->format('F Y') }}</span></p>
    </div>
    <div class="uk-flex journal_navigation navigation">
        <div class="uk-width-auto">
            <a class="journal_calendar_left uk-button uk-icon-button fa fa-arrow-left"></a>
        </div>
        <div class="uk-width-auto uk-text-right">
            <a class="journal_calendar_right uk-button uk-icon-button fa fa-arrow-right"></a>
        </div>
    </div>
@endsection
@section('body')
    <div class="progress journal_progress"><div class="progress_percent" style="width: 0%;"></div></div>
    {{-- Journals Container --}}
    {{-- Ajax View Journals --}}
    <div id="journals"
         data-view_journals_url="{{ action('Web\Journal\JournalController@_ajaxViewJournalsGet') }}"
         data-date="{{ $date->format('Y-m') }}">
    </div>
@endsection