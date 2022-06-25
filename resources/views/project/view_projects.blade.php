@extends('template.master')
@section('css')
    {!! ($vs->css)('views/project/view_projects') !!}
@endsection
@section('js')
    {!! ($vs->js)('views/project/view_projects') !!}
@endsection
@section('sidebar')
    @include('library.project.projects_sidebar')
@endsection
@section('title-block')
    <p>All Projects</p>
@endsection
@section('body')
    <div class="section no-border-top">
        <div class="projects"
             data-view_projects_url="{{ action('Web\Project\ProjectController@_ajaxViewProjectsGet') }}">
        </div>
    </div>
@endsection