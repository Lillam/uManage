<div class="uk-flex uk-grid uk-grid-collapse" uk-grid>
    @foreach ($days as $day)
        <div class="timelog_day_wrapper {{ $day->format('d-m-Y') === $today ? 'today' : '' }}"
             data-date="{{ $day->format('d.m.Y') }}">
            {{--  The date region of the weekly calendar. --}}
            <div class="timelog_date">
                {{-- The Date Title --}}
                <span class="timelog_date_title uk-flex">
                    <span class="uk-width-auto">
                        <b>{{ $day->format('D') }}</b>
                        <span class="small">{{ $day->format('d.m') }}</span>
                    </span>
                    <span class="uk-width-expand uk-text-right">
                        <b>{{ ! empty($timelog_hours[$day->format('d-m-Y')]) ?
                            TimeLogRepository::convertTimelogTimeSpent($timelog_hours[$day->format('d-m-Y')], true): '0h'
                        }}</b>
                    </span>
                </span>
            </div>
            <div class="timelog_day_timelogs">
                @if (! empty($timelogs[$day->format('d-m-Y')]))
                    @foreach ($timelogs[$day->format('d-m-Y')] as $timelog)
                        <div class="timelog_entry" data-timelog_id="{{ $timelog->id }}">
                            <div class="timelog_title">
                                <b>{{ $timelog->task->getShortName() }}</b>
                            </div>
                            <div class="timelog_content">
                                <p>{{ $timelog->note }}</p>
                            </div>
                            <div class="uk-flex">
                                <div class="uk-width-expand timelog_task_project">
                                    <span class="badge"
                                          style="background-color: {{ $timelog->project->getColor() }}"
                                    >{{ $timelog->project->code }}-{{ $timelog->task->id }}</span>
                                </div>
                                <div class="uk-width-auto timelog_time_spent">
                                    <b>{{ $timelog->time_spent }}</b>
                                </div>
                            </div>
                            <div class="timelog_entry_options uk-flex uk-flex-middle uk-flex-center">
                                <a class="edit_timelog uk-flex uk-flex-center uk-flex-middle">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a class="delete_timelog uk-flex uk-flex-center uk-flex-middle">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                @endif
                <a class="add_timelog_entry" href="#add_timelog_modal" uk-toggle><i class="fa fa-plus"></i> Add</a>
            </div>
        </div>
    @endforeach
</div>