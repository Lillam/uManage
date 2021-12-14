{{--
This particular set of logic is only going to be called when we are making amends to a specific checklist group...
this is going to be loading in everything for a set checklist group in question for a particular task, this is going to
bring back all necessary checklist items in question and plum them into the checklist segment, rather than loading in the
entire set of checklists, and checklist items... when we only need to bring back a very particular set of data...
to note:
When updating other areas, this particular file will need to be updated in conjunction with:
library/task/task_checklists/view_task_checklists.blade.php
--}}
@foreach ($task_checklist_items as $task_checklist_item)
    <div class="task_checklist_item {{ $task_checklist_item->is_checked ? 'complete' : '' }}"
         data-task_checklist_item_id="{{ $task_checklist_item->id }}">
        <div class="task_checklist_item_name_wrapper uk-flex">
            <div class="uk-width-auto uk-flex uk-flex-middle task_checklist_item_checkbox_wrapper">
                <div>
                    <input type="checkbox"
                           class="uk-checkbox task_checklist_item_checkbox"
                           {{ $task_checklist_item->is_checked ? 'checked' : '' }}
                    />
                </div>
            </div>
            <div class="task_checklist_item_name uk-width-expand" contenteditable>{!! $task_checklist_item->name !!}</div>
            <div class="uk-width-auto task_checklist_item_options_wrapper uk-hidden">
                <a class="fa fa-check uk-button uk-button-primary disabled save_task_checklist_item_name"></a>
                <a class="fa fa-close uk-button uk-button-danger cancel_task_checklist_item_name"></a>
            </div>
            <div class="uk-width-auto task_checklist_item_options_dropdown_wrapper">
                <a class="fa fa-ellipsis-h uk-button dropdown_button"></a>
                <div class="dropdown">
                    <ul>
                        {{-- Deleting Task Checklist Items, JavaScript is attached to this particular
                        Element, if you are changing the name of this class, this will need to be
                        Changed within the javascript as well... --}}
                        <li><a class="delete_task_checklist_item"><i class="fa fa-trash"></i> Delete</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endforeach