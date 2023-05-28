<div class="dropdown open">
    <ul>
        @foreach ($tasks as $task)
            <li>
                <a data-task_id="{{ $task->id }}" data-project_id="{{ $task->project_id }}"
                >{!! $task->project->name !!} - {!! $task->name !!}</a>
            </li>
        @endforeach
    </ul>
</div>