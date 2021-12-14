@extends('template.master')
@section('css')
    {!! ($vs->css)('views/system/system_changelog/view_system_changelogs') !!}
@endsection
@section('body')
    {{-- Journal Navigation --}}
    <div class="system_changelog_navigation">
        <div class="uk-flex">
            <div class="uk-width-expand uk-flex uk-flex-middle">
                <span class="system_changelog_title">Changelogs</span>
            </div>
            <div class="uk-width-auto uk-border-left">
                <a class="uk-button uk-icon-button"
                   href="{{ action('System\SystemChangelogController@_editSystemChangelogGet', 'new') }}">
                    <i class="fa fa-plus"></i>
                </a>
            </div>
        </div>
    </div>
@endsection