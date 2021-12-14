@extends('template.master')
@section('css')
    {!! ($vs->css)('views/project/project_setting/view_project_setting') !!}
@endsection
@section('js')
    {!! ($vs->js)('views/project/project_setting/view_project_setting') !!}
@endsection
@section('body')
    <div class="project" data-update_project_url="{{ action('Project\ProjectSettingController@_editProjectSettingsPost') }}">
        <input type="hidden" name="project_id" value="{{ $project_setting->project_id }}" />
        <div class="project_settings_content">
            <div class="section">
                <h2 class="section_title">Options</h2>
                <div class="">
                    <label>Project Color</label>
                    <input type="color" class="project_color" value="{{ $project_setting->project->color }}" />
                    <div class="project_color_options uk-hidden uk-margin-top">
                        <a class="save_project_color uk-button uk-button-primary uk-button-small">Save</a>
                        <a class="cancel_project_color uk-button uk-button-danger uk-button-small">Cancel</a>
                    </div>
                </div>
            </div>
            <div class="section">
                <h2 class="section_title">Description</h2>
                <div class="description">
                    @if (! empty($project_setting->project->description))
                        {!! $project_setting->project->description !!}
                    @else
                        <span class="placeholder">Enter a description</span>
                    @endif
                </div>
            </div>
            <div class="section">
                <a href="{{ action('Project\ProjectController@_deleteProjectGet', $project_setting->project_id) }}"
                   class="delete_project uk-button uk-button-small uk-button-danger">Delete Project</a>
            </div>
        </div>
        <div class="project_settings_sidebar">
            <div class="uk-grid uk-grid-small" uk-grid>
                <div class="uk-width-1-1">
                    <label>Project Owner</label>
                </div>
            </div>
        </div>
    </div>
@endsection