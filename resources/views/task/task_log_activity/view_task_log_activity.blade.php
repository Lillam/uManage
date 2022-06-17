@extends('template.master')
@section('css')
    {!! ($vs->css)('views/task/task_log_activity/view_task_log_activity') !!}
@endsection
@section('js')
    {!! ($vs->js)('views/task/task_log_activity/view_task_log_activity') !!}
@endsection
@section('title-block')
    {{-- Task Log Activity Header --}}
    <div class="uk-width-expand">
        <span class="task_log_activity_title">Task Activity</span>
    </div>
    <div class="uk-flex task_log_activity_header">
        <div class="uk-width-auto">
            <a class="uk-icon-button uk-icon-button"><i class="fa fa-info-circle"></i></a>
        </div>
    </div>
@endsection
@section('sidebar')
    @include('library.project.projects_sidebar')
@endsection
@section('body')
    <div class="task_log_activity">
        <div class="task_logs">
            @foreach ($task_logs as $task_log_key => $task_log)
                <div class="task_log">
                    <div class="task_log_content uk-width-expand@m">
                        {{-- What user and when was this log created --}}
                        <div class="uk-flex task_log_created_by">
                            <div class="uk-width-expand">
                                <p>{!! UserPrinter::userBadge($task_log->user) !!} {{ $task_log->user->getFullName() }} @ {{ $task_log->when->format('d/m/Y @ H:i') }}</p>
                            </div>
                            <div class="uk-width-auto">
                                <a href="{{ $task_log->task->getUrl() }}" class="task_link">
                                    <span>{!! TaskPrinter::getProjectBadge($task_log->task) !!}</span>
                                    <span>{{ $task_log->task->name }}</span>
                                </a>
                            </div>
                        </div>
                        <p>{{ __("task/task_log.{$task_log->getType()}") }}</p>
                        @if ($task_log->old !== null || $task_log->new !== null)
                            <div class="uk-grid uk-grid-small uk-flex" uk-grid>
                                @if (! empty($task_log->old))
                                    <div class="uk-width-expand@m">
                                        <div class="task_log_old_content">
                                            {!! $task_log->old !!}
                                        </div>
                                    </div>
                                @endif
                                @if (! empty($task_log->new))
                                    <div class="uk-width-expand@m">
                                        <div class="task_log_new_content">
                                            {!! $task_log->new !!}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
            {!! $task_logs->links() !!}
        </div>
        <div class="task_logs_sidebar">

        </div>
    </div>
@endsection