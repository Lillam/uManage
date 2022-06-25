<div class="uk-grid uk-grid-collapse" uk-grid>
    @foreach ($dates as $date_key => $date)
        <div class="journal_finance_month" data-date="{{ $date_key }}">
            <a href="{{ action('Web\Journal\JournalDreamController@_viewJournalDreamGet', [$date_key]) }}">
                <div class="box_wrapper no-border">
                    <h2>{!! $date->title !!}</h2>
                    <div class="uk-flex">
                        <div class="uk-width-expand">
                            <p><span class="lost"><i class="fa fa-minus-circle"></i>£{{ $date->lost }}</span></p>
                        </div>
                        <div class="uk-width-expand uk-text-right">
                            <p><span class="gained"><i class="fa fa-plus-circle"></i>£{{ $date->gained }}</span></p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @endforeach
</div>