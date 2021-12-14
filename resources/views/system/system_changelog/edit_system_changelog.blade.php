@extends('template.master')
@section('js')
    {!! ($vs->js)('views/system/system_changelog/edit_system_changelog') !!}
@endsection
@section('body')
    <div class="navigation">
        <div class="uk-flex">
            <div class="uk-width-expand uk-flex uk-flex-middle">
                <span class="system_changelog_title">Making New Changelog</span>
            </div>
            <div class="uk-width-auto uk-border-left">
                <a class="uk-button uk-button-icon"><i class="fa fa-save"></i></a>
            </div>
        </div>
    </div>

    <div class="section">
        <h2 class="section_title">Making a new changelog</h2>
        <div class="content">
            <span class="placeholder">Enter the content</span>
        </div>
        <div class="content_options uk-margin-small-top uk-hidden">
            <a class="save save_content">Save</a>
            <a class="cancel cancel_content">Cancel</a>
        </div>
    </div>
@endsection