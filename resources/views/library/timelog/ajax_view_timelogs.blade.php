<div class="timelog_box_wrapper">
    @foreach ($timelogs as $timelog)
        <div class="timelog_entry" data-timelog_id="{{ $timelog->id }}">
            <div class="timelog_title">
                <b>{{ $timelog->task->getShortName() }}</b>
            </div>
            <div class="timelog_content">
                <p>{!! $timelog->getShortNote() !!}</p>
            </div>
            <div class="timelog_information uk-flex">
                <div class="timelog_task_project uk-width-expand">
                    <span class="badge"
                          style="background-color: {{ $timelog->project->getColor() }}"
                    >{{ $timelog->project->code }}-{{ $timelog->task->id }}</span>
                </div>
                <div class="timelog_time_spent uk-width-auto">
                    <b>{{ TimeLogRepository::convertTimelogTimeSpent($timelog->time_spent) }}</b>
                </div>
            </div>
            <a class="delete_timelog"><i class="fa fa-trash"></i></a>
        </div>
    @endforeach
</div>