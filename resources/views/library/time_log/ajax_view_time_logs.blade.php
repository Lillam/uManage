<div class="time_log_box_wrapper">
    @foreach ($timeLogs as $timeLog)
        <div class="time_log_entry" data-time_log_id="{{ $timeLog->id }}">
            <div class="time_log_title">
                <b>{{ $timeLog->task->getShortName() }}</b>
            </div>
            <div class="time_log_content">
                <p>{!! $timeLog->getShortNote() !!}</p>
            </div>
            <div class="time_log_information uk-flex">
                <div class="time_log_task_project uk-width-expand">
                    <span class="badge"
                          style="background-color: {{ $timeLog->project->getColor() }}"
                    >{{ $timeLog->project->code }}-{{ $timeLog->task->id }}</span>
                </div>
                <div class="time_log_time_spent uk-width-auto">
                    <b>{{ TimeLogRepository::convertTimeLogTimeSpent($timeLog->time_spent) }}</b>
                </div>
            </div>
            <a class="delete_time_log"><i class="fa fa-trash"></i></a>
        </div>
    @endforeach
</div>