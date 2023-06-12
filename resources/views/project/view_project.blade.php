@extends('template.master')
@section('css')
    {!! ($vs->css)('assets/vendor/select2/select2.min') !!}
    {!! ($vs->css)('views/task/view_task_list') !!}
    {!! ($vs->css)('views/project/view_project.css') !!}
@endsection
@section('js')
    {!! ($vs->js)('assets/vendor/select2/select2.min') !!}
    {!! ($vs->js)('views/project/view_project') !!}
    {!! ($vs->js)('views/task/view_tasks') !!}
@endsection
@section('title-block')
    <div class="uk-width-expand">
        <p>{{ $project->name }}</p>
    </div>
    <div class="task_navigation navigation uk-flex">
        <div class="uk-width-auto uk-hidden">
            <a class="tasks_navigation_left uk-button uk-button-icon"><i class="fa fa-arrow-left"></i></a>
        </div>
        <div class="uk-width-auto uk-flex uk-flex-middle">
            <p style="margin: 0;"><span class="count">0</span>/<span class="total">0</span></p>
        </div>
        <div class="uk-width-auto uk-hidden">
            <a class="tasks_navigation_right uk-button uk-button-icon"><i class="fa fa-arrow-right"></i></a>
        </div>
        <div class="uk-width-auto">
            <a class="project_settings_button uk-button uk-icon-button"
               href="{{ action('Web\Project\ProjectSettingController@_viewProjectSettingsGet', $project->code) }}">
                <i class="fa fa-cog"></i>
            </a>
        </div>
        <div class="uk-width-auto">
            <a class="project_advanced_search uk-button uk-icon-button"><i class="fa fa-search"></i></a>
        </div>
    </div>
@endsection
@section('sidebar')
    @include('library.project.projects_sidebar')
@endsection
@section('body')
    <div id="project">
        <div class="progress">
            <div class="progress_percent" style="width: {{ $project->getTaskCompletedPercentage() }}"></div>
        </div>

        {{-- Project Navigation --}}
{{--        <div class="navigation task_navigation">--}}
{{--            <div class="uk-flex">--}}
{{--                <div class="uk-width-expand uk-flex uk-flex-middle">--}}
{{--                    <span class="title">{{ $project->name }}</span>--}}
{{--                </div>--}}
{{--                <div class="uk-width-auto uk-border-left uk-hidden">--}}
{{--                    <a class="tasks_navigation_left uk-button uk-button-icon"><i class="fa fa-arrow-left"></i></a>--}}
{{--                </div>--}}
{{--                <div class="uk-width-auto uk-border-left uk-flex uk-flex-middle">--}}
{{--                    <p style="margin: 0;"><span class="count">0</span>/<span class="total">0</span></p>--}}
{{--                </div>--}}
{{--                <div class="uk-width-auto uk-border-left uk-hidden">--}}
{{--                    <a class="tasks_navigation_right uk-button uk-button-icon"><i class="fa fa-arrow-right"></i></a>--}}
{{--                </div>--}}
{{--                <div class="uk-width-auto uk-border-left">--}}
{{--                    <a class="project_settings_button uk-button uk-icon-button"--}}
{{--                       href="{{ action('Project\ProjectSettingController@_viewProjectSettingsGet', $project->code) }}">--}}
{{--                        <i class="fa fa-cog"></i>--}}
{{--                    </a>--}}
{{--                </div>--}}
{{--                <div class="uk-width-auto uk-border-left">--}}
{{--                    <a class="project_advanced_search uk-button uk-icon-button"><i class="fa fa-search"></i></a>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

        {{-- Acquiring the tasks that are currently residing in this particular project. --}}
        <div class="tasks"
             data-page="1"
             data-pagination="true"
             data-items_per_page="17"
             data-get_tasks_url="{{ route('projects.tasks.ajax') }}"
             data-project_id="{{ $project->id }}">
        </div>

        {{-- The project sidebar (this will be the filtration of the tasks and more so that the user can opt to see done/
         in progress/to do and more statuses or filters to do with this particular project in question... --}}
        <div class="project_sidebar">
            {{-- Statuses --}}
            <h2>Filters <a class="close_project_sidebar"><i class="fa fa-close"></i></a></h2>
            <label for="task_search" class="uk-hidden">Search for tasks</label>
            <input type="text" id="task_search" placeholder="Search...">
            <div class="uk-grid uk-grid-small">
                <div class="uk-width-1-1">
                    <h3>Task Statuses</h3>
                    <input type="hidden" id="task_statuses" value="{{ $filters->task_statuses }}">
                    {!! TaskStatusRepository::getTaskStatusCheckboxFilters(
                        explode(',', $filters->task_statuses)
                    ) !!}
                </div>
                {{-- Issue Types --}}
                <div class="uk-width-1-1">
                    <h3>Task Issue Types</h3>
                    <input type="hidden" id="task_issue_types" value="{{ $filters->task_issue_types }}">
                    {!! TaskIssueTypeRepository::getTaskIssueTypeCheckboxFilters(
                        explode(',', $filters->task_issue_types)
                    ) !!}
                </div>
                {{-- Task Priorities --}}
                <div class="uk-width-1-1">
                    <h3>Task Priorities</h3>
                    <input type="hidden" id="task_priorities" value="{{ $filters->task_priorities }}">
                    {!! TaskPriorityRepository::getTaskPriorityCheckboxFilters(
                        explode(',', $filters->task_priorities)
                    ) !!}
                </div>
            </div>
            <a class="clear_filters uk-button uk-button-small uk-button-primary">Clear</a>
        </div>
    </div>
@endsection