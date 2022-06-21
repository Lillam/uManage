@extends('template.master')
@section('css')
    {!! ($vs->css)('views/task/view_task_list') !!}
@endsection
@section('js')
    {!! ($vs->js)('model/task/task') !!}
    {!! ($vs->js)('views/task/view_tasks') !!}
@endsection
@section('title-block')
    <div class="uk-width-expand">
        <p>Tasks</p>
    </div>
    {{-- Task Navigation --}}
    <div class="uk-flex task_navigation">
        <div class="uk-width-auto uk-hidden">
            <a class="tasks_navigation_left uk-button uk-icon-button"><i class="fa fa-arrow-left"></i></a>
        </div>
        <div class="uk-width-auto uk-flex uk-flex-middle">
            <span class="count">0</span>/<span class="total">0</span>
        </div>
        <div class="uk-width-auto uk-hidden">
            <a class="tasks_navigation_right uk-button uk-icon-button"><i class="fa fa-arrow-right"></i></a>
        </div>
    </div>
@endsection
@section('sidebar')
    @include('library.project.projects_sidebar')
@endsection
@section('body')
    {{-- Task List --}}
    <div class="tasks"
         data-page="1"
         data-pagination="true"
         data-get_tasks_url="{{ route('projects.tasks.ajax') }}"
         data-items_per_page="25">
    </div>
@endsection