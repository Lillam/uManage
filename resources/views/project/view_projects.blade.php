@extends('template.master')
@section('css')
    {!! ($vs->css)('views/project/view_projects') !!}
@endsection
@section('js')
    {!! ($vs->js)('views/project/view_projects') !!}
@endsection
@section('body')
    <div class="section">
        <div class="projects"
             data-view_projects_url="{{ action('Project\ProjectController@_ajaxViewProjectsGet') }}">
        </div>
    </div>
@endsection