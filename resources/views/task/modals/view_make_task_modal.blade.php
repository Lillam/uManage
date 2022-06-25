<div class="make_task_modal" uk-modal data-make_task_url="{{ route('projects.tasks.task.create.ajax') }}">
    <div class="uk-modal-dialog uk-margin-auto-vertical">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-header">
            <h2 class="uk-modal-title">Create Task</h2>
        </div>
        <div class="uk-modal-body">
            <div class="uk-grid uk-grid-small" uk-grid>
                <div class="uk-width-1-1">
                    <label class="uk-display-block uk-margin-small-bottom" for="make_task_project_id">Project</label>
                    <select name="make_task_project_id" class="make_task_project_id" id="make_task_project_id">
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="uk-width-1-1">
                    <label class="uk-display-block uk-margin-small-bottom"
                           for="make_task_name"
                    >Task Name</label>
                    <input type="text"
                           name="make_task_name"
                           id="make_task_name"
                           class="uk-input make_task_name"
                    />
                </div>
                <div class="uk-width-1-1">
                    <label class="uk-display-block uk-margin-small-bottom"
                           for="make_task_description"
                    >Task Description</label>
                    <input type="text"
                           name="make_task_description"
                           id="make_task_description"
                           class="uk-input make_task_description"
                    />
                </div>
                <div class="uk-width-1-1 uk-text-right">
                    <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
                    <button class="uk-button uk-button-primary save_new_task" type="button">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>