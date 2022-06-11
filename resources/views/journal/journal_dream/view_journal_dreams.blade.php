@extends('template.master')
@section('css')
    {!! ($vs->css)('views/journal_dreams/view_journal_dreams') !!}
@endsection
@section('js')
    {!! ($vs->js)('views/journal_dreams/view_journal_dreams') !!}
@endsection
@section('sidebar')
    @include('library.journal.journals_sidebar')
@endsection
@section('title-block')
    <div class="uk-width-expand">
        <p><span class="journal_dreams_date">{{ $date->format('F Y') }}</span></p>
    </div>
    {{-- Journal Dreams Navigation --}}
    <div class="journal_dreams_navigation uk-flex">
        <div class="uk-width-auto">
            <a class="journal_dreams_calendar_left uk-button uk-icon-button fa fa-arrow-left"></a>
        </div>
        <div class="uk-width-auto uk-text-right">
            <a class="journal_dreams_calendar_right uk-button uk-icon-button fa fa-arrow-right"></a>
        </div>
    </div>
@endsection
@section('body')
    {{-- Displaying the calendar view of dreams in the system --}}
    <div id="journal_dreams"
         data-date="{{ $date->format('Y-m') }}"
         data-view_journal_dreams_url="{{ action('Journal\JournalDreamController@_ajaxViewJournalDreamsGet') }}">
    </div>
@endsection