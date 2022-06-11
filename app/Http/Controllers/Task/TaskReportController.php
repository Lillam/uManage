<?php

namespace App\Http\Controllers\Task;

use Illuminate\Http\Request;
use App\Models\Task\TaskStatus;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class TaskReportController extends Controller
{
    /**
    * @param Request $request
    * @return JsonResponse
    */
    public function _ajaxViewTasksReportGet(Request $request): JsonResponse
    {
        $task_statuses = TaskStatus::selectRaw('count(DISTINCT task.id) as task_count,' .
            'task_status.name,' .
            'task_status.color,' .
            'task_status.type')
            ->leftJoin('task', 'task.task_status_id', '=', 'task_status.id')
            ->groupBy('task_status.id')
            ->get();

        $tasks_in_todo             = [];
        $tasks_in_progress         = [];
        $tasks_in_completed        = [];
        $tasks_in_archived         = [];
        $tasks_in_todo_labels      = ['To Do', 'Total'];
        $tasks_in_progress_labels  = ['In Progress', 'Total'];
        $tasks_in_completed_labels = ['Completed', 'Total'];
        $tasks_in_archived_labels  = ['Archived', 'Total'];

        $total_tasks = 0;

        foreach ($task_statuses as $task_status) {
            if ($task_status->type === TaskStatus::$TYPE_TODO) {
                $tasks_in_todo[]        = $task_status->task_count;
                $tasks_in_todo_colors[] = "#{$task_status->color}";
            }

            if ($task_status->type === TaskStatus::$TYPE_IN_PROGRESS) {
                $tasks_in_progress[]        = $task_status->task_count;
                $tasks_in_progress_colors[] = "#{$task_status->color}";
            }

            if ($task_status->type === TaskStatus::$TYPE_DONE) {
                $tasks_in_completed[]        = $task_status->task_count;
                $tasks_in_completed_colors[] = "#{$task_status->color}";
            }

            if ($task_status->type === TaskStatus::$TYPE_ARCHIVED) {
                $tasks_in_archived[]        = $task_status->task_count;
                $tasks_in_archived_colors[] = "#{$task_status->color}";
            }

            $total_tasks += $task_status->task_count;
        }

        $tasks_in_todo[]      = $tasks_in_progress[] =
        $tasks_in_completed[] = $tasks_in_archived[] = $total_tasks;

        $tasks_in_todo_colors[]      = $tasks_in_progress_colors[] =
        $tasks_in_completed_colors[] = $tasks_in_archived_colors[] = '#eee';

        return response()->json([
            'tasks_in_todo'             => $tasks_in_todo,
            'tasks_in_todo_labels'      => $tasks_in_todo_labels,
            'tasks_in_todo_colors'      => $tasks_in_todo_colors,
            'tasks_in_progress'         => $tasks_in_progress,
            'tasks_in_progress_labels'  => $tasks_in_progress_labels,
            'tasks_in_progress_colors'  => $tasks_in_progress_colors,
            'tasks_in_completed'        => $tasks_in_completed,
            'tasks_in_completed_labels' => $tasks_in_completed_labels,
            'tasks_in_completed_colors' => $tasks_in_completed_colors,
            'tasks_in_archived'         => $tasks_in_archived,
            'tasks_in_archived_labels'  => $tasks_in_archived_labels,
            'tasks_in_archived_colors'  => $tasks_in_archived_colors
        ]);
    }
}