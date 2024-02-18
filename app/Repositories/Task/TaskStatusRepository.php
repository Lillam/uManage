<?php

namespace App\Repositories\Task;

use App\Models\Task\TaskStatus;

class TaskStatusRepository
{
    /**
    * This method is just for acquiring all the task statuses that are in the system, there is no restrictions on this
    * particular method, as stated, getTaskStatuses...
    *
    * @return TaskStatus[]|\Illuminate\Database\Eloquent\Collection
    */
    public static function getTaskStatuses()
    {
        $task_statuses = TaskStatus::all();
        return $task_statuses;
    }

    /**
    * This method is entirely for returning a list of task statuses in the form of checkboxes the will be used for
    * filtration on the frontend, this is currently used on the project page, quite possibly planned for more however
    * clicking on one of these will look for tasks that are currently sat within these status ids.
    *
    * @param array $checked_task_status_ids
    * @return string
    */
    public static function getTaskStatusCheckboxFilters($checked_task_status_ids = []): string
    {
        // acquire all the task statuses in the system...
        $task_statuses = self::getTaskStatuses();

        $html = '';

        foreach ($task_statuses as $task_status) {
            $checked = '';

            if (in_array($task_status->id, $checked_task_status_ids)) {
                $checked = 'checked=checked';
            }

            $html .= '<div class="checkbox_row">';
                $html .= '<input type="checkbox" id="' . $task_status->name . '"
                                 class="uk-checkbox task_status_checkbox"
                                 data-task_status_id="' . $task_status->id . '"
                                 ' . $checked . '>';
                $html .= '<label for="' . $task_status->name . '">';
                    $html .= $task_status->name;
                $html .= '</label>';
            $html .= '</div>';
        }

        return $html;
    }
}
