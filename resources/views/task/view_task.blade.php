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
    <div id="task" class="section no-border-top"
        data-project_id="{{ $task->project_id }}"
        data-task_id="{{ $task->id }}"
        data-edit_task_url="{{ action('Task\TaskController@_ajaxEditTaskPost') }}">
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
            <div class="task_title_wrapper">
                <h2 class="task_name name" contenteditable>{{ $task->name }}</h2>
            </div>
            {{-- Task Description --}}
            <h2 class="section_title">Description</h2>
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
            {{--Task Checklists--}}
            <h2 class="section_title">Sub Tasks</h2>
            <div class="task_checklists_wrapper"
                 data-make_task_checklist_url="{{ action('Task\TaskChecklistController@_ajaxMakeTaskChecklistPost') }}"
                 data-view_task_checklists_url="{{ action('Task\TaskChecklistController@_ajaxViewTaskChecklistsGet') }}"
                 data-edit_task_checklist_url="{{ action('Task\TaskChecklistController@_ajaxEditTaskChecklistPost') }}"
                 data-delete_task_checklist_url="{{ action('Task\TaskChecklistController@_ajaxDeleteTaskChecklistPost') }}"
                 data-edit_task_checklist_order_url="{{ action('Task\TaskChecklistController@_ajaxEditTaskChecklistOrderPost') }}"
                 data-edit_task_checklist_zipped_url="{{ action('Task\TaskChecklistController@_ajaxEditTaskChecklistEditZipStatus') }}"
                 data-make_task_checklist_item_url="{{ action('Task\TaskChecklistItemController@_ajaxMakeTaskChecklistItemPost') }}"
                 data-view_task_checklist_items_url="{{ action('Task\TaskChecklistItemController@_ajaxViewTaskChecklistItemsGet') }}"
                 data-edit_task_checklist_item_url="{{ action('Task\TaskChecklistItemController@_ajaxEditTaskChecklistItemPost') }}"
                 data-edit_task_checklist_item_order_url="{{ action('Task\TaskChecklistItemController@_ajaxEditTaskChecklistItemOrderPost') }}"
                 data-delete_task_checklist_item_url="{{ action('Task\TaskChecklistItemController@_ajaxDeleteTaskChecklistItemPost') }}"
            ></div>
            {{-- Task Comments --}}
            <div class="task_comments"
                 data-page="1"
                 data-view_task_comments_url="{{ action('Task\TaskCommentController@_ajaxViewTaskCommentsGet') }}"
                 data-delete_task_comment_url="{{ action('Task\TaskCommentController@_ajaxDeleteTaskCommentPost') }}">
            </div>
        </div>
        {{-- Task  Sidebar  (Information about the task) --}}
        @include('task.task_assets.view_task_sidebar')
    </div>
@endsection