<div class="uk-grid uk-grid-collapse" uk-grid>
    @foreach ($dates as $date_key => $date)
        <div class="journal_month" data-date="{{ $date_key }}">
            @if ($date->journal instanceof \App\Models\Journal\Journal)
                <span class="journal_active"></span>
            @else
                <span class="journal_inactive"></span>
            @endif
            <a href="{{ action('Journal\JournalController@_viewJournalGet', [$date_key]) }}">
                <div class="box_wrapper">
                    <h2>{!! $date->title !!}</h2>
                    <div class="journal_overall_content">
                        @if (! empty($date->journal) && ! empty($date->journal->overall))
                            <p> {{ $date->journal->getShortOverall() }}</p>
                        @else
                            <p class="placeholder"><span>This is some placeholder text that no one should really be able to see just a little bit more...</span></p>
                        @endif
                    </div>
                    <div class="uk-flex">
                        <div class="uk-width-expand">
                            <span class="journal_rating_badge {{ $date?->journal?->rating === null ? 'journal_placeholder_rating' : '' }}">
                                {{-- If there is no rating for the day in question, then we are simply going to
                                display a placeholder, these will be different and outlined rather than a filled
                                icon so that there is a difference between the two elements --}}
                                @for ($i = 1; $i < 6; $i++)
                                    <i class="fa fa-star {{ $date?->journal?->rating >= $i ? 'active' : 'placeholder' }}"
                                        {!! $date?->journal?->rating >= $i ? "style='color: {$date?->journal->ratingColor()}'" : "" !!}
                                    ></i>
                                @endfor
                            </span>
                        </div>
                        @if ($date->journal instanceof \App\Models\Journal\Journal && $date->journal->achievements->isNotEmpty())
                            <div class="uk-width-auto uk-text-right uk-flex uk-flex-middle">
                                <span class="journal_achievements">
                                    <i class="fa fa-trophy"></i> {{ $date->journal->achievements->count() }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </a>
        </div>
    @endforeach
</div>