@extends('template.master')
@section('css')
    {!! ($vs->css)('views/system/system_changelog/view_system_changelogs') !!}
@endsection
@section('title-block')
    <div class="uk-width-expand">
        <span class="system_changelog_title">Changelogs</span>
    </div>
    {{-- Journal Navigation --}}
    <div class="system_changelog_navigation">
        <a class="uk-button uk-icon-button"
           href="{{ route('system.changelogs.edit', 'new') }}"
        ><i class="fa fa-plus"></i></a>
    </div>
@endsection
@section('sidebar')
    <h2>Change logs</h2>
@endsection
@section('body')

@endsection