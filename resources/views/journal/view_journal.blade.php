@extends('template.master')
@section('css')
    {!! ($vs->css)('views/journal/view_journal') !!}
@endsection
@section('js')
    {!! ($vs->js)('views/journal/view_journal') !!}
@endsection
@php($journal_achievements_enabled = true)
{{--@php($journal_achievements_enabled = in_array(--}}
{{--    \App\Http\Controllers\Journal\JournalAchievementController::class,--}}
{{--    $vs->user_module_access--}}
{{--))--}}
@section('title-block')
    <div class="uk-width-expand">
        <p>{{ $journal->when->format('jS F Y') }}</p>
    </div>
    <div class="uk-flex navigation">
        <div class="uk-width-auto">
            <a href="{{ $yesterday_link }}"
               class="uk-button uk-icon-button fa fa-arrow-left journal_navigation_button"></a>
        </div>
        <div class="uk-width-auto">
            <a href="{{ $tomorrow_link }}"
               class="uk-button uk-icon-button fa fa-arrow-right journal_navigation_button"></a>
        </div>
        <div class="uk-width-auto">
            <a class="uk-button uk-icon-button journal_information fa fa-info-circle"></a>
        </div>
        <div class="uk-width-auto">
            <a class="uk-button uk-icon-button delete_journal fa fa-trash"></a>
        </div>
    </div>
@endsection
@section('sidebar')
    @include('library.journal.journals_sidebar')
@endsection
@section('body')
    {{-- Editing content of the journal entry today --}}
    <div id="journal" class="{{ ($journal_achievements_enabled) ? '' : 'journal_sidebar_closed' }}"
        data-journal_id="{{ $journal->id }}"
        data-edit_journal_url="{{ action('Web\Journal\JournalController@_ajaxEditJournalPost') }}"
        data-delete_journal_url="{{ action('Web\Journal\JournalController@_ajaxDeleteJournalPost') }}">
        <div class="journal_content">
            <div class="section no-border-top">
                <h2 class="section_title">Overall</h2>
                <div class="overall box">{!! $journal->overall !== ''
                    ? $journal->overall
                    : '<span class="placeholder">Talk about the overall day</span>' !!}
                </div>
                <div class="uk-hidden overall_options note-save-cancel-options">
                    <a class="save_overall uk-button uk-button-small uk-button-primary">Save</a>
                    <a class="cancel_overall uk-button uk-button-small uk-button-danger">Cancel</a>
                </div>
            </div>
            <div class="section">
                <h2 class="section_title">Lowest Part of the Day?</h2>
                <div class="lowest_point box">{!! $journal->lowest_point !== '' ?
                    $journal->lowest_point
                    : '<span class="placeholder">Talk about the lowest part of the day</span>' !!}
                </div>
                <div class="uk-hidden lowest_point_options">
                    <a class="save_lowest_point uk-button uk-button-primary">Save</a>
                    <a class="cancel_lowest_point uk-button uk-button-danger">Cancel</a>
                </div>
            </div>
            <div class="section">
                <h2 class="section_title">Highest Part of the Day?</h2>
                <div class="highest_point box">{!! $journal->highest_point !== ''
                    ? $journal->highest_point
                    : '<span class="placeholder">Talk about the highest part of the day</span>' !!}
                </div>
                <div class="uk-hidden highest_point_options">
                    <a class="save_highest_point uk-button uk-button-primary">Save</a>
                    <a class="cancel_highest_point uk-button uk-button-danger">Cancel</a>
                </div>
            </div>
        </div>
        {{--  Journal Achievements... what have i achieved today. --}}
        <div class="journal_sidebar">
            <h2>Rate the day</h2>
            <div class="">
                @for ($i = 1; $i <= 5; ++$i)
                    <a class="journal_rating {{ $journal->rating >= $i ? 'fa fa-star' : 'far fa-star' }}"
                       data-rating="{{ $i }}">
                    </a>
                @endfor
            </div>
            @if ($journal_achievements_enabled)
                <h2>What have I achieved today?</h2>
                <div id="journal_achievements"
                     data-get_journal_achievements_url="{{ action('Web\Journal\JournalAchievementController@_ajaxViewJournalAchievementsGet') }}"
                     data-make_journal_achievements_url="{{ action('Web\Journal\JournalAchievementController@_ajaxMakeJournalAchievementPost') }}"
                     data-edit_journal_achievements_url="{{ action('Web\Journal\JournalAchievementController@_ajaxEditJournalAchievementPost') }}"
                     data-drop_journal_achievements_url="{{ action('Web\Journal\JournalAchievementController@_ajaxDeleteJournalAchievementPost') }}"
                ></div>
                <div class="uk-flex">
                    <div class="uk-width-expand">
                        <label for="new_journal_achievement" class="uk-hidden">Add new Achievement</label>
                        <input type="text"
                               id="new_journal_achievement"
                               class="new_journal_achievement"
                               placeholder="Add New Achievement"
                        />
                    </div>
                    <div class="uk-width-auto uk-hidden save_new_journal_achievement_wrapper">
                        <a class="fa fa-check uk-button uk-button-primary disabled save_new_journal_achievement"></a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection