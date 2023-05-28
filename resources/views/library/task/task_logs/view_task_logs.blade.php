@foreach ($taskLogs as $taskLog)
    <div class="task_log">
        <div class="task_log_header uk-flex">
            <div class="uk-width-auto task_log_user">
                {!! UserPrinter::userBadge($taskLog->user, false) !!}
            </div>
            <div class="uk-width-expand task_log_title">
                @if ($taskLog->title !== null)
                    {{ $taskLog->title }}
                @endif
            </div>
            <div class="uk-width-auto">
                <span class="task_log_date">{{ $taskLog->when->format('l d F Y @ H:i') }}</span>
            </div>
        </div>
        @if($taskLog->new_text !== null || $taskLog->old_text !== null)
            <div class="uk-flex task_log_content">
                @if ($taskLog->old_text !== null)
                    <div class="uk-width-expand task_log_old_content">
                        {!! $taskLog->old_text !!}
                    </div>
                @endif
                @if ($taskLog->new_text !== null)
                    <div class="uk-width-expand task_log_new_content">
                        {!! $taskLog->new_text !!}
                    </div>
                @endif
            </div>
        @endif
    </div>
@endforeach

@if ($taskLogs->isEmpty())
    <p>There is no current logs in the system for this task...</p>
@endif

@if ($total_pages > 1)
    <div class="pagination uk-flex">
        <div class="uk-width-expand">
            @if ($page > 1)
                <a class="paginate_previous">Previous</a>
            @endif
        </div>
        <div class="uk-width-auto">
            {{ $page }} / {{ $totalPages }}
        </div>
        <div class="uk-width-expand uk-text-right">
            @if ($page < $totalPages)
                <a class="paginate_next">Next</a>
            @endif
        </div>
    </div>
@endif