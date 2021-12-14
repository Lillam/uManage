<div class="uk-flex project_task_board">
    @foreach ($task_statuses as $task_status)
        <div class="uk-width-expand">
            <h2>{{ $task_status->name }}</h2>
            <div class="project_task_status_tasks" uk-sortable="handle: .board_task; group: .task_statuses">
                @foreach ($task_status->status_tasks as $task)
                    <div class="board_task">
                        <div class="uk-flex">
                            <div class="uk-width-auto uk-margin-small-right">
                                {!! TaskPrinter::getProjectBadge($task) !!}
                            </div>
                            <div class="uk-width-expand">
                                <h3><a href="{{ action('Task\TaskController@_viewTaskGet', [$task->project->code, $task->id]) }}">{{ $task->name }}</a></h3>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>