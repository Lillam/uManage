@extends('template.master')
@section('css')
    {!! ($vs->css)('views/journal_diets/view_journal_diets') !!}
@endsection
@section('js')
    {!! ($vs->js)('views/journal_diets/view_journal_diets') !!}
@endsection
@section('sidebar')
    @include('library.journal.journals_sidebar')
@endsection
@section('title-block')
    <div class="uk-width-expand">
        <p><span class="journal_diet_date">{{ $date->format('F Y') }}</span></p>
    </div>
    <div class="uk-flex journal_diet_navigation navigation">
        <div class="uk-width-auto">
            <a class="journal_diet_calendar_left uk-button uk-icon-button fa fa-arrow-left"></a>
        </div>
        <div class="uk-width-auto uk-text-right">
            <a class="journal_diet_calendar_right uk-button uk-icon-button fa fa-arrow-right"></a>
        </div>
    </div>
@endsection
@section('body')
    <div id="journal_diets"
        data-view_journal_diets_url="{{ action('Web\Journal\JournalDietController@_ajaxViewJournalDietsGet') }}"
        data-date="{{ $date->format('Y-m') }}">
    </div>
@endsection
