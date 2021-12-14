<?php

namespace App\Repositories\Task;

use App\Models\Task\TaskIssueType;

class TaskIssueTypeRepository
{
    /**
    * @return TaskIssueType[]|\Illuminate\Database\Eloquent\Collection
    */
    public static function getTaskIssueTypes()
    {
        $task_issue_types = TaskIssueType::all();
        return $task_issue_types;
    }

    /**
    * This method is entirely for returning a list of task issue types in the form of checkboxes the will be used for
    * filtration on the frontend, this is currently used on the project page, quite possibly planned for more however
    * clicking on one of these will look for tasks that are currently sat within these issue type ids.
    *
    * @param array $checked_task_issue_type_ids
    * @return string
    */
    public static function getTaskIssueTypeCheckboxFilters($checked_task_issue_type_ids = []): string
    {
        $task_issue_types = self::getTaskIssueTypes();

        $html = '';
        foreach ($task_issue_types as $task_issue_type_key => $task_issue_type) {

            $checked = '';

            // if the array that we have passed is in the checked+task_issue_type_ids that we will ahve passed via the
            // filters on a controller, and if it happens to be in them, then the checkbox in question will have the
            // attribute checked against it.
            if (in_array($task_issue_type->id, $checked_task_issue_type_ids)) {
                $checked = 'checked=checked';
            }

            $html .= '<div class="checkbox_row">';
                $html .= '<input type="checkbox" id="' . $task_issue_type->name . '" 
                                     class="uk-checkbox task_issue_type_checkbox" 
                                     data-task_issue_type_id="' . $task_issue_type->id . '"
                                     ' . $checked . '>';
                $html .= '<label for="' . $task_issue_type->name . '">';
                $html .= $task_issue_type->name;
                $html .= '</label>';
            $html .= '</div>';
        }

        return $html;
    }
}