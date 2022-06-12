@extends('template.master')
@section('css')
    {!! ($vs->css)('views/journals/view_journals') !!}
    {!! ($vs->css)('views/journal_dashboard/view_journal_dashboard') !!}
@endsection
@section('sidebar')
    @include('library.journal.journals_sidebar')
@endsection
@section('title-block')
    <div class="uk-width-expand">
        <p>Dashboard</p>
    </div>
@endsection
@section('body')
    <div class="section no-border-top">
        <h2 class="section_title">Last 365 Days Ratings</h2>
        <div class="uk-grid uk-grid-small day_ratings" uk-grid>
            <div class="uk-width-1-3@l uk-width-1-1">
                <div class="day_rating good uk-flex uk-flex-middle">
                    <p><span>{{ $good_days }}</span>Total Amazing Days</p>
                </div>
            </div>
            <div class="uk-width-1-3@l uk-width-1-2@s uk-width-1-1">
                <div class="day_rating average uk-flex uk-flex-middle">
                    <p><span >{{ $average_days }}</span>Total Average Days</p>
                </div>
            </div>
            <div class="uk-width-1-3@l uk-width-1-2@s uk-width-1-1">
                <div class="day_rating bad uk-flex uk-flex-middle">
                    <p><span>{{ $bad_days }}</span>Total Bad Days</p>
                </div>
            </div>
        </div>
        <h2 class="section_title uk-margin-top">Last 5 Days Journals</h2>
        <div class="uk-grid uk-grid-small" uk-grid>
            @foreach ($last_5_days as $journal)
                <div class="uk-width-1-5@xl uk-width-1-4@l uk-width-1-3@m uk-width-1-2@s uk-width-1-1 journal_month">
                    <a href="{{ route('journals.journal', $journal->when->format('Y-m-d')) }}">
                        <div class="box_wrapper no-border ">
                            <h2>{!! $journal->when->format('l dS Y') !!}</h2>
                            <div class="journal_overall_content">
                                <p> {{ $journal->getShortOverall(70) }}</p>
                            </div>
                            <div class="uk-flex">
                                <div class="uk-width-expand">
                                    <span class="journal_rating_badge {{ $journal?->rating === null ? 'journal_placeholder_rating' : '' }}">
                                        {{-- If there is no rating for the day in question, then we are simply going to
                                        display a placeholder, these will be different and outlined rather than a filled
                                        icon so that there is a difference between the two elements --}}
                                        @for ($i = 1; $i < 6; $i++)
                                            <i class="fa fa-star {{ $journal->rating >= $i ? 'active' : 'placeholder' }}"
                                                {!! $journal->rating >= $i ? "style='color: {$journal->ratingColor()}'" : "" !!}
                                            ></i>
                                        @endfor
                                    </span>
                                </div>
                                @if ($journal instanceof \App\Models\Journal\Journal && $journal->achievements->isNotEmpty())
                                    <div class="uk-width-auto uk-text-right uk-flex uk-flex-middle">
                                        <span class="journal_achievements">
                                            <i class="fa fa-trophy"></i> {{ $journal->achievements->count() }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection