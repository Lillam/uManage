<?php

namespace App\Http\Controllers\Web\Project\Task;

use Illuminate\Http\Request;
use App\Models\Task\TaskStatus;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Web\Controller;

class TaskReportController extends Controller
{
    /**
    * @param Request $request
    * @return JsonResponse
    */
    public function _ajaxViewTasksReportGet(Request $request): JsonResponse
    {
        $task_statuses = TaskStatus::query()
            ->selectRaw(
                'count(DISTINCT task.id) as task_count,' .
                'task_status.name,' .
                'task_status.color,' .
                'task_status.type'
            )
            ->leftJoin('task', 'task.task_status_id', '=', 'task_status.id')
            ->groupBy('task_status.id')
            ->get();

        $tasks = [
            1 => ['color' => null, 'count' => 0],
            2 => ['color' => null, 'count' => 0],
            3 => ['color' => null, 'count' => 0],
            4 => ['color' => null, 'count' => 0]
        ];

        $total_tasks = 0;

        foreach ($task_statuses as $task_status) {
            $tasks[$task_status->type]['color'] = '#' . $task_status->color;
            $tasks[$task_status->type]['count'] = $task_status->task_count;
            $total_tasks += $task_status->task_count;
        }

        return response()->json([
            'tasks'       => $tasks,
            'total_tasks' => $total_tasks
        ]);
    }
}
