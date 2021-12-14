@foreach ($task_logs as $task_log)
    <div class="task_log">
        <div class="task_log_header uk-flex">
            <div class="uk-width-auto task_log_user">
                {!! UserPrinter::userBadge($task_log->user, false) !!}
            </div>
            <div class="uk-width-expand task_log_title">
                @if($task_log->title !== null)
                    {{ $task_log->title }}
                @endif
            </div>
            <div class="uk-width-auto">
                <span class="task_log_date">{{ $task_log->when->format('l d F Y @ H:i') }}</span>
            </div>
        </div>
        @if($task_log->new_text !== null || $task_log->old_text !== null)
            <div class="uk-flex task_log_content">
                @if ($task_log->old_text !== null)
                    <div class="uk-width-expand task_log_old_content">
                        {!! $task_log->old_text !!}
                    </div>
                @endif
                @if ($task_log->new_text !== null)
                    <div class="uk-width-expand task_log_new_content">
                        {!! $task_log->new_text !!}
                    </div>
                @endif
            </div>
        @endif
    </div>
@endforeach

@if($task_logs->isEmpty())
    <p>There is no current logs in the system for this task...</p>
@endif

@if ($total_pages > 1)
    <div class="pagination uk-flex">
        <div class="uk-width-expand">
            @if($page > 1)
                <a class="paginate_previous">Previous</a>
            @endif
        </div>
        <div class="uk-width-auto">
            {{ $page }} / {{ $total_pages }}
        </div>
        <div class="uk-width-expand uk-text-right">
            @if ($page < $total_pages)
                <a class="paginate_next">Next</a>
            @endif
        </div>
    </div>
@endif