{{-- This particular file is utilised for universally displaying tasks wherever they are required when utilising ajax,
this is the file that will be returned every time we are requesting to get some tasks from the system and can return
them in this particular format, all tasks will be standardised to the same visuals no matter where we are looking at
them. --}}
{{-- Iterate over all the tasks... --}}
@foreach ($tasks as $task_key => $task)
    <a href="{{ $task->getUrl() }}">
        <div class="task_row">
            <div class="uk-flex">
                <div class="uk-width-auto uk-flex uk-flex-middle task_project">
                    {!! TaskPrinter::getProjectBadge($task) !!}
                </div>
                <div class="uk-width-expand uk-flex">
                    <span class="task_name uk-width-expand uk-flex uk-flex-middle">
                        <span>{{ $task->name }}</span>
                    </span>
                    <div class="task_assigned_to uk-flex uk-flex-middle">
                        {!! UserPrinter::userBadge($task->task_assigned_user, false) !!}
                    </div>
                </div>
                <div class="uk-width-auto uk-flex uk-flex-middle">
                    {!! $task->task_status->getBadge() !!}
                </div>
            </div>
            {{-- Just commenting this particular bit out as I am not 100% sold on this, however; it wcould potentially
             be useful to be able to document how many items a task has... (a number on the row) --}}
            {{--<div class="task_progress">
                <div class="progress"><div class="progress_percent" style="width: {{ $task->getTaskProgress() }};"></div></div>
            </div>--}}
        </div>
    </a>
@endforeach
{{-- If there aren't any results then we are going to be letting the user know that there are no results for
the tasks/project/filteringg that they're trying to view... --}}
@if ($tasks->isEmpty())
    <p>Looks like there aren't any results...</p>
@endif