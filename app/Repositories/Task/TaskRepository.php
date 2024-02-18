<?php

namespace App\Repositories\Task;

use App\Models\Task\Task;
use App\Models\Task\TaskStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskRepository
{
    /**
    * This method is entirely for bringing back the search and will be returning the html for the search data of tasks
    * the user will be searching for a task whether that's by a numerical value, or whether that's in the name of the
    * task... the data will be iterated over and be placed into a dropdown so that javascript can take this html and
    * dump it to the frontend.
    *
    * @param $tasks
    * @return string
    */
    public static function displayTaskDropdownSearch($tasks): string
    {
        return view('task.task_assets.view_task_dropdown_search', compact('tasks'))->render();
    }

    /**
    * This method is designed for getting all tasks with some passed filter arguments, the filter arguments will be
    * required in object format. (object) $filters [];
    * the following variables that will be required in this are:
    *
    * @param array|object $filters
    * @return Collection|LengthAwarePaginator
    */
    public static function getTasks(array|object $filters = []): Collection|LengthAwarePaginator
    {
        $tasks = Task::query()
            ->select('*')
            ->with([
                'project',
                'taskIssueType',
                'taskStatus',
                'taskPriority',
                'taskAssignedUser'
            ]);

        // if the project id has been specified, then we are only going to be wanting to get the tasks that are currently
        // sitting within this particular passed project id, otherwise, if we have not set project, then we are looking
        // to receive all tasks that are within the system regardless of project.
        if (! empty($filters->project_id)) {
            $tasks = $tasks->where('task.project_id', '=', $filters->project_id);
        }

        // if the task statuses has been specified then we are only looking for tasks that are against the specific
        // task status ids that we are passing through to this filter, the status ids are going to be pushed up in a
        // comma separated value... which we will implode on a ',' and then look for tasks where in those ids.
        if (! empty($filters->task_statuses)) {
            $tasks = $tasks->whereIn('task.task_status_id', explode(',', $filters->task_statuses));
        }

        // if the task issue types has been specified then we are only looking for tasks that are against the specific
        // task issue type ids that we are passing through to this filter, the task issue ids are going to be pushed up
        // in a comma separated value... which we will implode on a ',' and then look for tasks in those passed ids.
        if (! empty($filters->task_issue_types)) {
            $tasks = $tasks->whereIn('task.task_issue_type_id', explode(',', $filters->task_issue_types));
        }

        // if the task priorities has been specified then we are only looking for tasks that are against the specific
        // task priority ids that we are passing through to this filter... the task priority ids are going to be pushed
        // up in a comma separated value... which we will implode in a ',' and then look for tasks with those passed ids
        if (! empty($filters->task_priorities)) {
            $tasks = $tasks->whereIn('task.task_priority_id', explode(',', $filters->task_priorities));
        }

        // if we have passed through a search filter, then we are going to look through tasks that have the name similar
        // to what the user in question has opted to look for, currently all we are going to be searching for is the
        // tasks name solely.
        if (! empty($filters->search)) {
            $tasks = $tasks->whereRaw("task.name LIKE '%{$filters->search}%'");
        }

        // return the collection, however, before we are doing this, we are going to check whether the pagination filter
        // has been passed or not, and if it has; then we are going to return a subset amount of tasks, and this will be
        // determined where ever the implementation is being included.
        return (bool) $filters->pagination === true
            ? $tasks->paginate($filters->tasks_per_page)
            : $tasks->get();
    }

    /**
    * @param Collection|LengthAwarePaginator $tasks
    * @return JsonResponse
    */
    public static function listView(Collection|LengthAwarePaginator $tasks): JsonResponse
    {
        return response()->json([
            'html'         => view('library.task.task.ajax_view_tasks', compact(
                'tasks'
            ))->render(),
            'previous_url' => $tasks->previousPageUrl(),
            'next_url'     => $tasks->nextPageUrl(),
            'count'        => $tasks->currentPage() === 1
                ? $tasks->count()
                : $tasks->count() + (($tasks->perPage() * $tasks->currentPage()) - $tasks->perPage()),
            'total'        => $tasks->total()
        ]);
    }

    /**
    * @param Collection|LengthAwarePaginator $tasks
    * @return JsonResponse
    */
    public static function boardView(Collection|LengthAwarePaginator $tasks): JsonResponse
    {
        $task_statuses = self::sortTasksIntoTypes($tasks);

        return response()->json([
            'html' => view('library.task.task.ajax_view_tasks_board', compact(
                'task_statuses')
            )->render()
        ]);
    }

    /**
    * @param Collection|LengthAwarePaginator $tasks
    * @return Collection|LengthAwarePaginator
    */
    public static function sortTasksIntoTypes(Collection|LengthAwarePaginator $tasks): Collection|LengthAwarePaginator
    {
        $task_statuses = TaskStatus::query()
            ->select('*')
            ->where('id', '!=', TaskStatus::$TYPE_ARCHIVED)
            ->get()
            ->keyBy('id');

        foreach ($task_statuses as $task_status) {
            $task_status->status_tasks = collect();
        }

        $tasks->map(function ($task) use (&$task_statuses) {
            $task_statuses->get($task->taskStatus->id)->status_tasks->put($task->id, $task);
        });

        return $task_statuses;
    }
}
