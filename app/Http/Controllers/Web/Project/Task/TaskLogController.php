<?php

namespace App\Http\Controllers\Web\Project\Task;

use Throwable;
use Illuminate\View\View;
use App\Models\Task\TaskLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\Factory;
use App\Http\Controllers\Web\Controller;
use App\Repositories\Task\TaskLogRepository;
use Illuminate\Contracts\Foundation\Application;

class TaskLogController extends Controller
{
    /**
    * @param Request $request
    * @return JsonResponse
    * @throws Throwable
    */
    public function _ajaxViewTaskLogsGet(Request $request): JsonResponse
    {
        $taskId = (int) $request->input('task_id');
        $page    = (int) $request->input('page') ?: 1;
        $limit   = 5;
        $offset  = $limit * ($page - 1);

        // acquire the total number of task logs in the system so that we are going to be able to calculate how many
        // pages there are. if (the current page is the page we are on, then the pagination will need to be cancelled
        $taskLogCount = TaskLog::query()
            ->where('task_id', '=', $taskId)
            ->count();

        // acquire the total number of pages that we are going to be able to show, this will be needed to see if we can
        // go left or right.
        $totalPages = ceil($taskLogCount / $limit);

        // acquire all the task logs that are sitting in the system against this particular task, and then we are going
        // to offset by a page, as well as limit it to a small number so that the user will be able to flick through
        // the logs in the system.
        $taskLogs = TaskLog::query()
            ->where('task_id', '=', $taskId)
            ->orderBy('when', 'desc')
            ->limit($limit)
            ->offset($offset)
            ->get();

        // throw the task logs into the sort task logs repository in order to get this ordered in the way that the
        // frontend is desiring the data.
        $taskLogs = TaskLogRepository::sortTaskLogs($taskLogs->keyBy('id'));

        return response()->json([
            'html' => view('library.task.task_logs.view_task_logs', compact(
                'taskLogs',
                'page',
                'totalPages'
            ))->render()
        ]);
    }

    /**
    * This method is for displaying a timeline on all that's been happening on editing tasks; the user will come to
    * this page and see everything that's been happening, ordered by most recent, the further you go, the older the
    * change had been made... this page will be a hub for being able to keep up to date with everything that had been
    * happening on the tasks.
    *
    * @param Request $request
    * @return Application|Factory|View
    */
    public function _viewTaskLogActivityGet(Request $request): Application|Factory|View
    {
        $task_logs = TaskLog::query()
            ->select('*')
            ->with([
                'task',
                'taskChecklist',
                'taskChecklistItem',
                'taskComment',
                'project',
                'user'
            ])
            ->orderBy('when', 'desc')
            ->simplePaginate(10);

        $this->vs->set('currentPage', 'page.projects.tasks.activity')
                 ->set('title',        '- Task Activity');

        return view('task.task_log_activity.view_task_log_activity', compact(
            'task_logs'
        ));
    }

    /**
    * This method will simply be returning html that the ajax will be dumping into the page and re-adjusting the
    * content that is already on page, this will just grab the next set of 15 in the task... and if there are any others
    * in the database, then we are going to return a load more button with the next url as well, and if the
    * next url is null, then we are going to return nothing.
    *
    * @param Request $request
    * @return Application|Factory|View
    */
    public function _ajaxViewTaskLogActivityGet(Request $request): Application|Factory|View
    {
        $task_logs = TaskLog::query()->select('*');

        if ($project_id = $request->input('project_id')) {
            $task_logs = $task_logs->where('project_id', '=', $project_id);
        }

        if ($task_id = $request->input('task_id')) {
            $task_logs = $task_logs->where('task_id', '=', $task_id);
        }

        $task_logs = $task_logs->paginate(15);

        return view('task.task_log_activity.ajax_view_task_log_activity', compact(
            'task_logs'
        ));
    }
}
