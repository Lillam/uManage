<div class="task_sidebar">
    <div class="uk-grid uk-grid-small" uk-grid>
        {{-- Task Reporter --}}
        <div class="uk-width-1-1">
            <label class="label"><i class="fa fa-user"></i> Created By</label>
            <div class="task_reporter_user">
                @if ($task->taskUser !== null)
                    {!! UserPrinter::userBadge($task->taskUser, false, $task->project->getColor()) !!}
                    <span class="badge placeholder"
                          style="background-color: {{ $task->project->getColor() }}"
                    >{{ $task->taskUser->getFullName() }}</span>
                @endif
            </div>
        </div>
        {{-- Task Assigned To --}}
        <div class="uk-width-1-1">
            <label class="label"><i class="fa fa-user"></i> Assignee</label>
            <div class="task_assigned_to task_dropdown_wrapper">
                @if ($task->taskAssignedUser !== null)
                    {!! UserPrinter::userBadge($task->taskAssignedUser, false, $task->project->getColor()) !!}
                    <span class="badge"
                          style="background-color: {{ $task->project->getColor() }}"
                    >{{ $task->taskAssignedUser->getFullName() }}</span>
                @else
                    <span class="badge placeholder"
                          style="background-color: {{ $task->project->getColor() }}"
                    >Unassigned</span>
                @endif
                <div class="task_dropdown" id="assigned_user_id">
                    @foreach ($task->project_users as $task_project_user_key => $task_project_user)
                        <a class="assigned_user"
                           data-id="{{ $task_project_user->id }}"
                        >{{ $task_project_user->getFullName() }}</a>
                    @endforeach
                </div>
            </div>
        </div>
        {{-- Task Status --}}
        <div class="uk-width-1-1">
            <label class="label"><i class="fa fa-warning"></i> Status</label>
            <div class="task_dropdown_wrapper">
                @if ($task->task_status_id !== null)
                    <span class="badge"
                          style="background-color: {{ $task->project->getColor() }}"
                    >{{ $task->taskStatus->name }}</span>
                @else
                    <span class="badge"
                          style="background-color: {{ $task->project->getColor() }}"
                    >Add Task Status</span>
                @endif
                <div class="task_dropdown" id="task_status_id">
                    @foreach ($task_statuses as $task_status_key => $task_status)
                        <a class="task_status" data-id="{{ $task_status->id }}">{{ $task_status->name }}</a>
                    @endforeach
                </div>
            </div>
        </div>
        {{-- Task Issue Type --}}
        <div class="uk-width-1-1">
            <label class="label"><i class="fa fa-bug"></i> Issue Type</label>
            <div class="task_dropdown_wrapper">
                @if ($task->taskIssueType !== null)
                    <span class="badge"
                          style="background-color: {{ $task->project->getColor() }}"
                    >{{ $task->taskIssueType->name }}</span>
                @else
                    <span class="badge"
                          style="background-color: {{ $task->project->getColor() }}"
                    >Add Task Issue Type</span>
                @endif
                <div class="task_dropdown" id="task_issue_type_id">
                    @foreach ($task_issue_types as $task_issue_type_key => $task_issue_type)
                        <a class="task_issue_type" data-id="{{ $task_issue_type->id }}">{{ $task_issue_type->name }}</a>
                    @endforeach
                </div>
            </div>
        </div>
        {{-- Task Priority --}}
        <div class="uk-width-1-1">
            <label class="label"><i class="fa fa-bullhorn"></i> Priority</label>
            <div class="task_dropdown_wrapper">
                @if ($task->taskPriority !== null)
                    <span class="badge"
                          style="background-color: {{ $task->project->getColor() }}"
                    >{{ $task->taskPriority->name }}</span>
                @else
                    <span class="badge"
                          style="background-color: {{ $task->project->getColor() }}"
                    >Add Task Priority</span>
                @endif
                <div class="task_dropdown" id="task_priority_id">
                    @foreach ($task_priorities as $task_priority_key => $task_priority)
                        <a class="task_priority" data-id="{{ $task_priority->id }}">{{ $task_priority->name }}</a>
                    @endforeach
                </div>
            </div>
        </div>
        {{-- Task Watchers --}}
        <div class="uk-width-1-1">
            <label class="label"><i class="fa fa-eye"></i> Watchers</label>
            <div class="task_dropdown_wrapper">
                @forelse ($task->taskWatcherUsers as $taskWatcherUser)
                    <div style="margin-bottom: 5px;">
                        <span class="badge placeholder"
                              style="background-color: {{ $task->project->getColor() }}"
                        >{{ $taskWatcherUser->user->getFullName() }}</span>
                    </div>
                @empty
                    <span class="badge"
                          style="background-color: {{ $task->project->getColor() }}"
                    >Add Task Watcher</span>
                @endforelse
            </div>
        </div>
        {{-- Task Due Date --}}
        <div class="uk-width-1-1">
            <label class="label"><i class="fa fa-clock-o"></i> Due Date</label>
            <div class="task_dropdown_wrapper">
                <span class="badge placeholder"
                      style="background-color: {{ $task->project->getColor() }}"
                >{{ $task->due_date !== null ? $task->due_date->format('D d M Y') : 'Add Due Date' }}</span>
            </div>
        </div>
        {{-- Timelogging --}}
        @if ($task->taskTimeLogs->isNotEmpty())
            <div class="uk-width-1-1">
                <label class="label"><i class="fa fa-calendar"></i> Time Logs</label>
                @foreach ($task->taskTimeLogs as $timeLog)
                    <div class="task_time_log_row uk-flex">
                        <div class="uk-width-auto task_time_log_user">
                            {!! UserPrinter::userBadge($timeLog->user, false, $task->project->getColor()) !!}
                        </div>
                        <div class="uk-width-auto task_time_log_time_spent">
                            {{ TimeLogRepository::convertTimelogTimeSpent($timeLog->time_spent) }}
                        </div>
                        <div class="uk-width-expand uk-flex uk-flex-middle task_time_log_time_spent_percent">
                            <div class="progress">
                                <div class="progress_percent"
                                     style="width: {{ (($timeLog->time_spent / $task->total_time_logged) * 100) }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>