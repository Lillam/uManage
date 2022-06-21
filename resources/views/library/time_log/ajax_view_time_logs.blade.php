<div class="time_log_box_wrapper">
    @foreach ($time_logs as $time_log)
        <div class="time_log_entry" data-time_log_id="{{ $time_log->id }}">
            <div class="time_log_title">
                <b>{{ $time_log->task->getShortName() }}</b>
            </div>
            <div class="time_log_content">
                <p>{!! $time_log->getShortNote() !!}</p>
            </div>
            <div class="time_log_information uk-flex">
                <div class="time_log_task_project uk-width-expand">
                    <span class="badge"
                          style="background-color: {{ $time_log->project->getColor() }}"
                    >{{ $time_log->project->code }}-{{ $time_log->task->id }}</span>
                </div>
                <div class="time_log_time_spent uk-width-auto">
                    <b>{{ TimeLogRepository::convertTimeLogTimeSpent($time_log->time_spent) }}</b>
                </div>
            </div>
            <a class="delete_time_log"><i class="fa fa-trash"></i></a>
        </div>
    @endforeach
</div>