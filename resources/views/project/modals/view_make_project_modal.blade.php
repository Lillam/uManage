<div class="make_project_modal" uk-modal data-make_project_url="{{ action('Project\ProjectController@_ajaxCreateProjectPost') }}">
    <div class="uk-modal-dialog uk-margin-auto-vertical">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-header">
            <h2 class="uk-modal-title">Create Project</h2>
        </div>
        <div class="uk-modal-body">
            <div class="uk-grid uk-grid-small" uk-grid>
                <div class="uk-width-1-1">
                    <label class="uk-display-block uk-margin-small-bottom" for="make_project_name">Project Name</label>
                    <input type="text" name="make_project_name" id="make_project_name" class="uk-input make_project_name" />
                </div>
                <div class="uk-width-1-1">
                    <label class="uk-display-block uk-margin-small-bottom" for="make_project_code">Project Code (if left empty, will be first 2 characters)</label>
                    <input type="text" name="make_project_code" id="make_project_code" class="uk-input make_project_code" />
                </div>
                <div class="uk-width-1-1">
                    <label class="uk-display-block uk-margin-small-bottom" for="make_project_color">Project Colour</label>
                    <input type="color" name="make_project_color" id="make_project_color" class="make_project_color" value="#ffa500"/>
                </div>
            </div>
            <p class="uk-text-right">
                <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
                <button class="uk-button uk-button-primary save_new_project" type="button">Save</button>
            </p>
        </div>
    </div>
</div>