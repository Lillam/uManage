{{-- This particular file is entirely ajaxed, this file will be loaded after the view_task.blade has been loaded in
prior to this trying to be called in, if the request is made successfully to the task checklists then we are going to
be rendering this particular checklist sets of elements... --}}
@if ($taskChecklists->isNotEmpty())
<div class="section">
    <h2 class="section_title">Sub Tasks</h2>
    <div class="task_checklists" uk-sortable="handle: .task_checklist_options_dropdown_wrapper">
        @foreach ($taskChecklists as $taskChecklist)
            <div class="task_checklist uk-width-1-1 box_wrapper {{ $taskChecklist->is_zipped ? 'zipped' : '' }}"
                 data-task_checklist_id="{{ $taskChecklist->id }}">
                <div class="task_checklist_name_wrapper uk-flex">
                    <div class="task_checklist_name uk-width-expand" contenteditable>{!! $taskChecklist->name !!}</div>
                    <div class="uk-width-auto task_checklist_options_wrapper uk-hidden">
                        <a class="fa fa-check uk-button uk-button-primary disabled save_task_checklist_name"></a>
                        <a class="fa fa-close uk-button uk-button-danger cancel_task_checklist_name"></a>
                    </div>
                    <div class="uk-width-auto task_checklist_options_dropdown_wrapper">
                        <a class="fa fa-ellipsis-h uk-button dropdown_button"></a>
                        <div class="dropdown">
                            <ul>
                                <li><a class="task_checklist_zip"><i class="fa fa-minus-square"></i> Zip</a></li>
                                {{-- Deleting Task Checklists, JavaScript is attached to this particular
                                Element, if you are changing the name of this class, this will need to be
                                Changed within the javascript as well... --}}
                                <li><a class="delete_task_checklist"><i class="fa fa-trash-alt"></i> Delete</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="checklist_group_progress">
                    <div class="progress">
                        <div class="progress_percent"
                             style="width: {{ $taskChecklist->getTaskChecklistItemPercentProgress() }}"
                        ></div>
                    </div>
                </div>
                <div class="task_checklist_items"
                     uk-sortable="handle: .task_checklist_item_options_dropdown_wrapper; group: task_checklist_items">
                    @include('library.task.task_checklist_items.view_task_checklist_items', [
                        'taskChecklistItems' => $taskChecklist->taskChecklistItems
                    ])
                </div>
                <div class="new_task_checklist_item uk-flex">
                    <div class="uk-width-expand">
                        <label for="new_task_checklist_item_input" class="uk-hidden">Add New Checklist Item</label>
                        <input id="new_task_checklist_item_input"
                               class="new_task_checklist_item_input"
                               placeholder="New Checklist Item..."
                        />
                    </div>
                    <div class="uk-width-auto task_checklist_item_save_wrapper uk-hidden">
                        <a class="uk-button save_new_task_checklist_item uk-button-primary disabled">
                            <i class="fa fa-check"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif
<div class="section">
    <div class="new_task_checklist uk-flex">
        <div class="uk-width-expand">
            <label for="new_task_checklist_input" class="uk-hidden">Add New Checklist</label>
            <input id="new_task_checklist_input"
                   class="new_task_checklist_input"
                   placeholder="New Checklist..."
            />
        </div>
        <div class="uk-width-auto task_checklist_save_wrapper uk-hidden">
            <a class="uk-button save_new_task_checklist uk-button-primary disabled">
                <i class="fa fa-check"></i>
            </a>
        </div>
    </div>
</div>