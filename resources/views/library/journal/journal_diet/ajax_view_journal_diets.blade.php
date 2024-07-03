<div class="uk-grid uk-grid-collapse" uk-grid>
    @foreach ($dates as $date_key => $date)
        <div class="journal_month" data-date="{{ $date_key }}">
            @if ($date->journalDiet instanceof \App\Models\Journal\JournalDiet)
                <span class="journal_active"></span>
            @else
                <span class="journal_inactive"></span>
            @endif
            <a href="{{ action('Web\Journal\JournalController@_viewJournalGet', [$date_key]) }}">
                <div class="box_wrapper no-border">
                    <h2>{!! $date->title !!}</h2>
                </div>
            </a>
        </div>
    @endforeach
</div>
