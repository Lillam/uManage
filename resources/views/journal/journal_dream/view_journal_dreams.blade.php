@extends('template.master')
@section('css')
    {!! ($vs->css)('views/journal_dreams/view_journal_dreams') !!}
@endsection
@section('js')
    {!! ($vs->js)('views/journal_dreams/view_journal_dreams') !!}
@endsection
@section('body')
    {{-- Journal Dreams Navigation --}}
    <div class="journal_dreams_navigation navigation">
        <div class="uk-flex">
            <div class="uk-width-expand uk-flex uk-flex-middle">
                <span class="journal_dreams_date">{{ $date->format('F Y') }}</span>
            </div>
            <div class="uk-width-auto">
                <a class="journal_dreams_calendar_left fa fa-arrow-left"></a>
            </div>
            <div class="uk-width-auto uk-text-right">
                <a class="journal_dreams_calendar_right fa fa-arrow-right"></a>
            </div>
        </div>
    </div>
    {{-- Displaying the calendar view of dreams in the system --}}
    <div id="journal_dreams"
         data-date="{{ $date->format('Y-m') }}"
         data-view_journal_dreams_url="{{ action('Journal\JournalDreamController@_ajaxViewJournalDreamsGet') }}">
    </div>
@endsection