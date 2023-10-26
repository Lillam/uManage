@extends('template.master')
@section('css')
    @cssAsset('views/user/view_user')
    @cssAsset('views/task/view_task_list')
    @cssAsset('views/project/view_projects')
    @cssAsset('views/time_log/view_time_logs')
@endsection
@section('js')
    @jsAsset('assets/vendor/chart/chart')
    @jsAsset('views/task/view_tasks')
    @jsAsset('views/project/view_projects')
    @jsAsset('views/time_log/view_time_logs')
    @jsAsset('views/task/task_report/view_task_report.js')
@endsection
@section('body')
    <div class="section">
        <div class="user-welcome">
            <h2>Welcome Back <span>Liam Taylor</span></h2>
            <p>It is currently the {{ (new DateTime())->format(\App\Helpers\DateTime\DateTimeHelper::FORMAT_Daynth_M_Y) }}</p>
        </div>
        <h2 class="section_title">Productivity</h2>
        <div class="projects"
             data-view_projects_url="{{ route('projects.list.ajax') }}"
             data-view_mode="slider"
        ></div>
    </div>
    <div class="section">
        <div class="task_report" data-get_task_report_url="{{ route('projects.tasks.report.ajax') }}">
            <div class="uk-grid uk-grid-small" uk-grid>
                <div class="uk-width-1-4@m uk-width-1-2@s">
                    <div class="graph-card uk-flex uk-flex-center">
                        <h2>To Do</h2>
                        <div class="pie tasks_in_todo"></div>
                    </div>
                </div>
                <div class="uk-width-1-4@m uk-width-1-2@s">
                    <div class="graph-card uk-flex uk-flex-center">
                        <h2>In Progress</h2>
                        <div class="pie tasks_in_progress"></div>
                    </div>
                </div>
                <div class="uk-width-1-4@m uk-width-1-2@s">
                    <div class="graph-card uk-flex uk-flex-center">
                        <h2>Completed</h2>
                        <div class="pie tasks_in_completed"></div>
                    </div>
                </div>
                <div class="uk-width-1-4@m uk-width-1-2@s">
                    <div class="graph-card uk-flex uk-flex-center">
                        <h2>Archived</h2>
                        <div class="pie tasks_in_archived"></div>
                    </div>
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