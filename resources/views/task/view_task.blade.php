@extends('template.master')
@section('css')
    {!! ($vs->css)('views/task/task_checklists/view_task_checklists') !!}
    {!! ($vs->css)('views/task/task_comments/view_task_comments') !!}
    {!! ($vs->css)('views/task/task_timelogs/view_task_logs') !!}
    {!! ($vs->css)('views/task/view_task') !!}
@endsection
@section('js')
    {!! ($vs->js)('views/task/task_checklists/view_task_checklists') !!}
    {!! ($vs->js)('views/task/task_comments/view_task_comments') !!}
    {!! ($vs->js)('views/task/task_timelogs/view_task_logs') !!}
    {!! ($vs->js)('views/task/view_task') !!}
@endsection
@section('sidebar')
    @include('library.project.projects_sidebar')
@endsection
@section('title-block')
    <div class="uk-width-expand">
        <p>{!! TaskPrinter::getProjectBadge($task, $task->name) !!}</p>
    </div>
@endsection
@section('body')
    <div id="task"
        data-project_id="{{ $task->project_id }}"
        data-task_id="{{ $task->id }}"
        data-edit_task_url="{{ route('projects.tasks.task.edit.ajax') }}">
        <div class="task_content">
            {{-- Task Title --}}
{{--            <div class="navigation task_title_wrapper uk-flex">--}}
{{--                <div class="uk-width-auto uk-flex uk-flex-middle">--}}
{{--                    {!! TaskPrinter::getProjectBadge($task) !!}--}}
{{--                </div>--}}
{{--                <div class="uk-width-expand uk-flex uk-flex-middle">--}}
{{--                    <h2 class="task_name name" contenteditable>{{ $task->name }}</h2>--}}
{{--                </div>--}}
{{--                <div class="uk-width-auto uk-flex uk-flex-middle">--}}
{{--                    <a href="{{ action('Task\TaskController@_deleteTaskGet', [$task->project->code, $task->id]) }}"--}}
{{--                       class="task_delete uk-button uk-icon-button">--}}
{{--                        <i class="fa fa-trash"></i>--}}
{{--                    </a>--}}
{{--                </div>--}}
{{--                <div class="uk-width-auto uk-flex uk-flex-middle">--}}
{{--                    <a class="task_info uk-button uk-icon-button">--}}
{{--                        <i class="fa fa-info-circle"></i>--}}
{{--                    </a>--}}
{{--                </div>--}}
{{--            </div>--}}
            <div class="section no-border-top">
                <h2 class="section_title">Title</h2>
                <div class="task_title_wrapper">
                    <h2 class="task_name name" contenteditable>{{ $task->name }}</h2>
                </div>
                {{-- Task Description --}}
                <h2 class="section_title uk-margin-top">Description</h2>
                <div class="description box">
                    @if (! empty($task->description))
                        {!! $task->description !!}
                    @else
                        <span class="placeholder">Enter a description</span>
                    @endif
                </div>
                <div class="description_options uk-margin-small-top uk-hidden">
                    <a class="save save_description">Save</a>
                    <a class="cancel cancel_description">Cancel</a>
                </div>
            </div>
            {{--Task Checklists--}}
            <div class="task_checklists_wrapper"
                 data-make_task_checklist_url="{{ route('projects.tasks.task.checklists.create.ajax') }}"
                 data-view_task_checklists_url="{{ action('Web\Project\Task\TaskChecklistController@_ajaxViewTaskChecklistsGet') }}"
                 data-edit_task_checklist_url="{{ action('Web\Project\Task\TaskChecklistController@_ajaxEditTaskChecklistPost') }}"
                 data-delete_task_checklist_url="{{ action('Web\Project\Task\TaskChecklistController@_ajaxDeleteTaskChecklistPost') }}"
                 data-edit_task_checklist_order_url="{{ action('Web\Project\Task\TaskChecklistController@_ajaxEditTaskChecklistOrderPost') }}"
                 data-edit_task_checklist_zipped_url="{{ action('Web\Project\Task\TaskChecklistController@_ajaxEditTaskChecklistEditZipStatus') }}"
                 data-make_task_checklist_item_url="{{ route('projects.tasks.task.checklist.checklist_item.create.ajax') }}"
                 data-view_task_checklist_items_url="{{ action('Web\Project\Task\TaskChecklistItemController@_ajaxViewTaskChecklistItemsGet') }}"
                 data-edit_task_checklist_item_url="{{ action('Web\Project\Task\TaskChecklistItemController@_ajaxEditTaskChecklistItemPost') }}"
                 data-edit_task_checklist_item_order_url="{{ action('Web\Project\Task\TaskChecklistItemController@_ajaxEditTaskChecklistItemOrderPost') }}"
                 data-delete_task_checklist_item_url="{{ action('Web\Project\Task\TaskChecklistItemController@_ajaxDeleteTaskChecklistItemPost') }}"
            ></div>
            {{-- Task Comments --}}
            <div class="section">
                <div class="task_comments"
                     data-page="1"
                     data-view_task_comments_url="{{ action('Web\Project\Task\TaskCommentController@_ajaxViewTaskCommentsGet') }}"
                     data-delete_task_comment_url="{{ action('Web\Project\Task\TaskCommentController@_ajaxDeleteTaskCommentPost') }}">
                </div>
            </div>
        </div>
        {{-- Task  Sidebar  (Information about the task) --}}
        @include('task.task_assets.view_task_sidebar')
    </div>
@endsection