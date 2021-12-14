<?php

namespace App\Printers\Task;

use App\Models\Task\Task;
use App\Models\User\User;
use App\Models\Project\Project;

class TaskPrinter
{
    /**
    * @param Task $task
    * @return string
    */
    public static function getProjectBadge(Task $task): string
    {
        if (! $task instanceof Task || ! $task->project instanceof Project) {
            return '';
        }

        $html = "<span class='badge' style='background-color: {$task->project->getColor()}'>";
            $html .= "<span class='task_project_code'>{$task->project->code}-</span>";
            $html .= "$task->id";
        $html .= '</span>';

        return $html;
    }
}