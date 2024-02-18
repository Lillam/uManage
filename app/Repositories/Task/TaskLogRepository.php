<?php

namespace App\Repositories\Task;

use App\Models\Task\TaskLog;

class TaskLogRepository
{
    /**
    * Defining a static method of which will be utilised in these locations:
    * Task.php (Model)
    * TaskChecklist.php (Model)
    * TaskChecklistItem.php (Model)
    *
    * This method is utilised for just updating and inserting into the database, the most recent updates that had
    * happened against a task, task checklist or task checklist item, this method doesn't really need to return anything
    * in particular, other than just simply creating a database row.
    *
    * @param array $variables
    * @return void
    */
    public static function logTask(array $variables = [])
    {
        TaskLog::create($variables);
    }

    /**
    * @param $task_logs
    * @return array
    */
    public static function sortTaskLogs($task_logs)
    {
        $task_logs->map(function ($task_log) {
            // if the item happens to be a direct task edit... then we are going to set the handle method for this
            // entry point as handleTempTaskEdit... so that we can latch on some values to this particular object prior
            // to utilising it.
            if ($task_log->isTaskEdit()) {
                return self::handleTempTaskEdit($task_log);
            }

            // if the item happens to be a direct task checklist edit, then we are going to set the handle method for
            // this entry point as handleTempTaskChecklistEdit so that we can latch on some values to this particular
            // object prior to utilising it.
            if ($task_log->isTaskChecklistEdit()) {
                return self::handleTempTaskChecklistEdit($task_log);
            }

            // if the item happens to be a direct task checklist item edit then we are going to set the handle method
            // for this entry point as handleTempTaskChecklistItemEdit so that we can latch on some values to this
            // particular object prior to utilising it.
            if ($task_log->isTaskChecklistItemEdit()) {
                return self::handleTempTaskChecklistItemEdit($task_log);
            }
        });

        return $task_logs;
    }

    /**
    * The method for handling all of the task edit logs, which will sort them into new value orientations so we are able
    * to decide what goes into each log segment, this will be adding a lot of data onto the current log model by utilising
    * temporary values.
    *
    * @param TaskLog $task_log
    * @return TaskLog
    */
    public static function handleTempTaskEdit(TaskLog $task_log)
    {
        switch ($task_log->type) {
            case TaskLog::TASK_MAKE:
                $task_log->new_text = 'task created';
                break;
            case TaskLog::TASK_NAME:
                $task_log->title = 'Updated Task Name';
                $task_log->old_text = $task_log->old;
                $task_log->new_text = $task_log->new;
                break;
            case TaskLog::TASK_DESCRIPTION:
                $task_log->title = 'Updated Task Description';
                $task_log->old_text = $task_log->old;
                $task_log->new_text = $task_log->new;
                break;
            case TaskLog::TASK_DUE_DATE:
                $task_log->new_text = 'task Due date updated';
                break;
            // @todo this wants to be put back into the logging concept, as we would want to know what was deleted.
            // case TaskLog::TASK_DELETE:
            //     $task_log->new_text = 'task deleted';
            //     break;
        }

        return $task_log;
    }

    /**
    * This method is for handling all of the task checklist edit logs, which will sort them into new value orientations
    * so we are able to decide what goes into each log segment, this will be adding a lot of data onto the current log
    * model by utilising temporary values.
    *
    * @param TaskLog $task_log
    * @return TaskLog
    */
    public static function handleTempTaskChecklistEdit(TaskLog $task_log)
    {
        switch ($task_log->type) {
            case TaskLog::TASK_CHECKLIST_MAKE:
                $task_log->title = 'Created new Task Checklist';
                $task_log->new_text = $task_log->extra;
                break;
            case TaskLog::TASK_CHECKLIST_NAME:
                $task_log->title = 'Updated Task Checklist Name';
                $task_log->old_text=  $task_log->old;
                $task_log->new_text = $task_log->new;
                break;
            // @todo this wants to be put back into the logging concept, as we would want to know what was deleted.
            // case TaskLog::TASK_CHECKLIST_DELETE:
            //     $task_log->title = 'Deleted Task Checklist';
            //     $task_log->new_text = $task_log->extra;
            //     break;
        }

        return $task_log;
    }

    /**
    * This method is for handling all of the task checklist item edit logs which will sort them into new value
    * orientations so we are able to decide what goes into each log segment, this will be adding a lot of data onto the
    * current log model by utilising temporary values.
    *
    * @param TaskLog $task_log
    * @return TaskLog
    */
    public static function handleTempTaskChecklistItemEdit(TaskLog $task_log)
    {
        switch ($task_log->type) {
            case TaskLog::TASK_CHECKLIST_ITEM_MAKE:
                $task_log->title = 'Created Task Checklist Item';
                $task_log->new_text = $task_log->extra;
                break;
            case TaskLog::TASK_CHECKLIST_ITEM_NAME:
                $task_log->title = 'Updated Task Checklist Item Name';
                $task_log->new_text = $task_log->new;
                $task_log->old_text = $task_log->old;
                break;
            case TaskLog::TASK_CHECKLIST_ITEM_CHECKED:
                $task_log->title = (int) $task_log->new === 1 ? 'Checked Checklist Item' : 'Unchecked Checklist Item';
                $task_log->new_text = (int) $task_log->new === 1 ?
                    '<i class="fa fa-check-circle-o"></i> ' . $task_log->extra :
                    '<i class="fa fa-circle-o"></i> ' . $task_log->extra;
                break;
            // @todo this wants to be put back into the logging concept, as we would want to know what was deleted.
            // case TaskLog::TASK_CHECKLIST_ITEM_DELETE:
            //     $task_log->title = 'Deleted Task Checklist Item';
            //     $task_log->new_text = $task_log->extra;
            //     break;
        }

        return $task_log;
    }
}
