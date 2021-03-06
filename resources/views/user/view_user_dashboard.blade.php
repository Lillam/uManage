@extends('template.master')
@section('css')
    {!! ($vs->css)('views/user/view_user') !!}
    {!! ($vs->css)('views/task/view_task_list') !!}
    {!! ($vs->css)('views/project/view_projects') !!}
    {!! ($vs->css)('views/time_log/view_time_logs') !!}
@endsection
@section('js')
    {!! ($vs->js)('assets/vendor/chart/chart') !!}
    {!! ($vs->js)('views/task/view_tasks') !!}
    {!! ($vs->js)('views/project/view_projects') !!}
    {!! ($vs->js)('views/time_log/view_time_logs') !!}
    {!! ($vs->js)('views/task/task_report/view_task_report.js') !!}
@endsection
@section('body')
    <div class="section">
        <h2 class="section_title">Productivity</h2>
        <div class="projects"
             data-view_projects_url="{{ route('projects.list.ajax') }}"
             data-view_mode="slider"
        ></div>
    </div>
    <div class="section">
        <div class="task_report" data-get_task_report_url="{{ route('projects.tasks.report.ajax') }}">
            <div class="uk-grid" uk-grid>
                <div class="uk-width-1-4@m uk-width-1-2@s">
                    <canvas class="tasks_in_todo"
                            width="100vh"
                            height="77vh"
                    ></canvas>
                </div>
                <div class="uk-width-1-4@m uk-width-1-2@s">
                    <canvas class="tasks_in_progress"
                            width="100vh"
                            height="77vh"
                    ></canvas>
                </div>
                <div class="uk-width-1-4@m uk-width-1-2@s">
                    <canvas class="tasks_in_completed"
                            width="100vh"
                            height="77vh"
                    ></canvas>
                </div>
                <div class="uk-width-1-4@m uk-width-1-2@s">
                    <canvas class="tasks_in_archived"
                            width="100vh"
                            height="77vh"
                    ></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="section">
        <div class="uk-grid uk-grid-medium" uk-grid>
            <div class="uk-width-1-4@m">
                <h2 class="section_title">Recent Activity</h2>
                <div class="time_logs_box"
                     data-get_time_logs_url="{{ route('time-logs.ajax') }}"></div>
            </div>
            <div class="uk-width-1-2@m">
                <h2 class="section_title">Recent History</h2>
            </div>
        </div>
    </div>
@endsection