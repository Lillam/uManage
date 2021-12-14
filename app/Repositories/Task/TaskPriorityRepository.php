<?php

namespace App\Repositories\Task;

use App\Models\Task\TaskPriority;

class TaskPriorityRepository
{
    /**
    * @return TaskPriority[]|\Illuminate\Database\Eloquent\Collection
    */
    public static function getTaskPriorities()
    {
        return TaskPriority::all();
    }

    /**
    * This method is entirely for returning a list of task priorities in the form of checkboxes the will be used for
    * filtration on the frontend, this is currently used on the project page, quite possibly planned for more however
    * clicking on one of these will look for tasks that are currently sat within these priority ids.
    *
    * @param array $selected_task_priority_ids
    * @return string
    */
    public static function getTaskPriorityCheckboxFilters($selected_task_priority_ids = []): string
    {
        $task_priorities = self::getTaskPriorities();

        $html = '';
        foreach ($task_priorities as $task_priority_key => $task_priority) {
            $checked = in_array($task_priority->id, $selected_task_priority_ids)
                ? 'checked=checked' : '';

            $html .= '<div class="checkbox_row">';
                $html .= '<input type="checkbox" id="' . $task_priority->name . '" 
                                       class="uk-checkbox task_priority_checkbox" 
                                       data-task_priority_id="' . $task_priority->id . '"
                                       ' . $checked . '>';
                $html .= '<label for="' . $task_priority->name . '">';
                    $html .= $task_priority->name;
                $html .= '</label>';
            $html .= '</div>';
        }

        return $html;
    }
}