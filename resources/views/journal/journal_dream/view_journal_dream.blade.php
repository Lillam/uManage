@extends('template.master')
@section('css')
    {!! ($vs->css)('views/journal_dreams/view_journal_dream') !!}
@endsection
@section('js')
    {!! ($vs->js)('views/journal_dreams/view_journal_dream') !!}
@endsection
@section('sidebar')
    @include('library.journal.journals_sidebar')
@endsection
@section('body')
    <div id="journal_dream"
         data-edit_journal_dream_url="{{ action('Web\Journal\JournalDreamController@_editJournalDreamPost') }}"
         data-delete_journal_dream_url="{{ action('Web\Journal\JournalDreamController@_ajaxDeleteJournalDreamPost') }}"
         data-journal_dream_id="{{ $journal_dream->id }}">
        <div class="journal_dream_content">
            <div class="journal_dream_navigation_wrapper navigation">
                <div class="uk-flex">
                    <div class="uk-width-expand uk-flex uk-flex-middle">
                        <span>{{ $journal_dream->when->format('jS F Y') }}</span>
                    </div>
                    <div class="uk-width-auto uk-border-left">
                        <a href="{{ $yesterday_link }}"
                           class="uk-button uk-icon-button fa fa-arrow-left journal_dream_navigation"></a>
                    </div>
                    <div class="uk-width-auto uk-border-left">
                        <a href="{{ $tomorrow_link }}"
                           class="uk-button uk-icon-button fa fa-arrow-right journal_dream_navigation"></a>
                    </div>
                    <div class="uk-width-auto uk-border-left">
                        <a class="uk-button uk-icon-button delete_journal_dream"><i class="fa fa-trash"></i></a>
                    </div>
                    <div class="uk-width-auto uk-border-left">
                        <a class="uk-button uk-icon-button journal_dream_information"><i class="fa fa-info-circle"></i></a>
                    </div>
                </div>
            </div>
            <div class="section">
                <h2>Content</h2>
                <div class="content box">{!! $journal_dream->content !== ''
                        ? $journal_dream->content
                        : '<span class="placeholder">Talk about the dream</span>' !!}
                </div>
                <div class="uk-hidden content_options note-save-cancel-options">
                    <a class="save_content uk-button uk-button-small uk-button-primary">Save</a>
                    <a class="cancel_content uk-button uk-button-small uk-button-danger">Cancel</a>
                </div>

                <h2 class="uk-margin-top">Meaning</h2>
                <div class="meaning box">{!! $journal_dream->meaning !== ''
                        ? $journal_dream->meaning
                        : '<span class="placeholder">Talk about what you feel the dream meant</span>' !!}
                </div>
                <div class="uk-hidden meaning_options note-save-cancel-options">
                    <a class="save_meaning uk-button uk-button-small uk-button-primary">Save</a>
                    <a class="cancel_meaning uk-button uk-button-small uk-button-danger">Cancel</a>
                </div>
            </div>
        </div>

        <div class="journal_dream_sidebar">
            <h2>Rate the Dream</h2>
            <div class="">
                @for($i = 1; $i <= 5; ++$i)
                    <a class="journal_dream_rating {{ $journal_dream->rating >= $i ? 'fa fa-star' : 'far fa-star' }}"
                       data-rating="{{ $i }}">
                    </a>
                @endfor
            </div>
        </div>
    </div>
@endsection