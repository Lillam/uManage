<div class="uk-grid uk-grid-collapse" uk-grid>
    @foreach ($dates as $date_key => $date)
        <div class="journal_dream_month" data-date="{{ $date_key }}">
            @if (! empty($date->journal_dream))
                <span class="journal_dream_active"></span>
            @else
                <span class="journal_dream_inactive"></span>
            @endif
            <a href="{{ action('Journal\JournalDreamController@_viewJournalDreamGet', [$date_key]) }}">
                <div class="box_wrapper no-border">
                    <h2>{!! $date->title !!}</h2>
                    <div class="journal_dream_content">
                        @if (! empty($date->journal_dream) && ! empty($date->journal_dream->content))
                            <p> {{ $date->journal_dream->getShortContent() }}</p>
                        @else
                            <p class="placeholder"><span>This is some placeholder text that no one should really be able to see...</span></p>
                        @endif
                    </div>
                    <div class="uk-flex">
                        <div class="uk-width-expand">
                            <span class="journal_dream_rating_badge">
                                {{-- If there is no rating for the day in question, then we are simply going to
                                display a placeholder, these will be different and outlined rather than a filled
                                icon so that there is a difference between the two elements --}}
                                @for ($i = 1; $i < 6; $i++)
                                    <i class="fa fa-star {{ $date?->journal_dream?->rating >= $i ? 'active' : 'placeholder' }}"
                                        {!! $date?->journal_dream?->rating >= $i ? "style='color: {$date?->journal_dream?->ratingColor()}'" : "" !!}
                                    ></i>
                                @endfor
                            </span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @endforeach
</div>