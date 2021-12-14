@extends('template.master')
@section('css')
    {!! ($vs->css)('views/task/view_task_list') !!}
@endsection
@section('js')
    {!! ($vs->js)('model/task/task') !!}
    {!! ($vs->js)('views/task/view_tasks') !!}
@endsection
@section('body')
    {{-- Task Navigation --}}
    <div class="navigation task_navigation">
        <div class="uk-flex">
            <div class="uk-width-expand uk-flex uk-flex-middle">

            </div>
            <div class="uk-width-auto uk-border-left uk-hidden">
                <a class="tasks_navigation_left uk-button uk-button-icon"><i class="fa fa-arrow-left"></i></a>
            </div>
            <div class="uk-width-auto uk-border-left uk-flex uk-flex-middle">
                <p style="margin: 0;"><span class="count">0</span>/<span class="total">0</span></p>
            </div>
            <div class="uk-width-auto uk-border-left uk-hidden">
                <a class="tasks_navigation_right uk-button uk-button-icon"><i class="fa fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
    {{-- Task List --}}
    <div class="tasks"
         data-page="1"
         data-pagination="true"
         data-get_tasks_url="{{ action('Task\TaskController@_ajaxViewTasksGet') }}"
         data-items_per_page="25">
    </div>
@endsection