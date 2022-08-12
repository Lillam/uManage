<div class="uk-flex uk-grid uk-grid-collapse" uk-grid>
    @foreach ($days as $day)
        <div class="time_log_day_wrapper {{ $day->format('d-m-Y') === $today ? 'today' : '' }}"
             data-date="{{ $day->format('d.m.Y') }}">
            {{--  The date region of the weekly calendar. --}}
            <div class="time_log_date">
                {{-- The Date Title --}}
                <span class="time_log_date_title uk-flex">
                    <span class="uk-width-auto">
                        <b>{{ $day->format('D') }}</b>
                        <span class="small">{{ $day->format('d.m') }}</span>
                    </span>
                    <span class="uk-width-expand uk-text-right">
                        <b>{{ ! empty($time_log_hours[$day->format('d-m-Y')]) ?
                            TimeLogRepository::convertTimelogTimeSpent($timeLogHours[$day->format('d-m-Y')], true): '0h'
                        }}</b>
                    </span>
                </span>
            </div>
            <div class="time_log_day_time_logs">
                @if (! empty($timeLogs[$day->format('d-m-Y')]))
                    @foreach ($timeLogs[$day->format('d-m-Y')] as $timeLog)
                        <div class="time_log_entry" data-time_log_id="{{ $timeLog->id }}">
                            <div class="time_log_title">
                                <b>{{ $timeLog->task->getShortName() }}</b>
                            </div>
                            <div class="time_log_content">
                                <p>{{ $timeLog->note }}</p>
                            </div>
                            <div class="uk-flex">
                                <div class="uk-width-expand time_log_task_project">
                                    <span class="badge"
                                          style="background-color: {{ $timeLog->project->getColor() }}"
                                    >{{ $timeLog->project->code }}-{{ $timeLog->task->id }}</span>
                                </div>
                                <div class="uk-width-auto time_log_time_spent">
                                    <b>{{ $timeLog->time_spent }}</b>
                                </div>
                            </div>
                            <div class="time_log_entry_options uk-flex uk-flex-middle uk-flex-center">
                                <a class="edit_time_log uk-flex uk-flex-center uk-flex-middle">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a class="delete_time_log uk-flex uk-flex-center uk-flex-middle">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                @endif
                <a class="add_time_log_entry" href="#add_time_log_modal" uk-toggle><i class="fa fa-plus"></i> Add</a>
            </div>
        </div>
    @endforeach
</div>