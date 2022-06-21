<?php

namespace App\Http\Controllers\Project\Task;

use Throwable;
use App\Models\Task\Task;
use Illuminate\View\View;
use App\Models\Task\TaskLog;
use Illuminate\Http\Request;
use App\Models\Project\Project;
use App\Models\Task\TaskStatus;
use App\Events\Task\TaskMessage;
use App\Models\Task\TaskPriority;
use Illuminate\Http\JsonResponse;
use App\Models\Task\TaskIssueType;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Models\Project\ProjectSetting;
use Illuminate\Contracts\View\Factory;
use App\Repositories\Task\TaskRepository;
use App\Repositories\TimeLog\TimeLogRepository;
use App\Http\Controllers\Project\ProjectController;
use App\Repositories\Project\ProjectSettingRepository;

class TaskController extends Controller
{
    /**
    * This method is strictly for showing the tasks page, this will give the user all the tasks that the user has
    * against their name and list them all. this method will be taking request parameters which will allow the user
    * to search, order and do other things with the data that show on this page...
    *
    * @param Request $request
    * @return Factory|View
    */
    public function _viewTasksGet(Request $request): Factory|View
    {
        $this->vs->set('title', '- Tasks')
                 ->set('current_page', 'page.projects.tasks.list');

        \Illuminate\Support\Facades\Request::method();

        return view('task.view_tasks');
    }

    /**
    * This method is strictly for showing a singular task that the user has opted to look at, this will be taking the
    * project code as well as the task id and will look as such: /3/1 (project 3) (task 1) and will attempt to find a
    * combination with the two matching values and if it does, it will show that task, otherwise it will redirect the
    * user back to the view tasks page.
    *
    * @param Request $request
    * @param $project_code
    * @param $task_id
    * @return Factory|RedirectResponse|View
    */
    public function _viewTaskGet(Request $request, $project_code, $task_id): Factory|RedirectResponse|View
    {
        $project = Project::query()
            ->where('code', '=', $project_code)
            ->with([
                'user_contributors',
                'tasks' => function ($query) use ($task_id) {
                    $query->where('id', '=', $task_id);
                },
                'tasks.task_assigned_user',
                'tasks.task_watcher_users',
                'tasks.task_checklists',
                'tasks.task_checklists.task_checklist_items',
                'tasks.task_time_logs',
                'tasks.task_time_logs.user',
                'tasks.project'
            ])
            ->first();

        // checking whether the project that we're looking at exists in the system or not, after checking that this
        // exists, we then check to see  if the authenticated user trying to view it... has the permission to view this
        // particular task/project combination.
        if (! $project instanceof Project || Auth::user()->cannot('ProjectPolicy@viewProject', $project))
            return $project instanceof Project
                ? redirect()->to($project->getUrl())
                : redirect()->action([ProjectController::class, '_viewProjectsGet']);

        // if the task does not exist, aka, we have found the project, but no task was found under the project with an
        // id then... we are going to kick this back, likewise, we are also going to check if the user cannot edit the
        // task in question, then we are also going to kick the user back.
        if (
            ! ($task = $project->tasks->first()) instanceof Task ||
            $this->vs->get('user')->cannot('TaskPolicy@viewTask', $task)
        ) return redirect()->to($project->getUrl());

        // assuming we have made it here - we have done everything that we needed with the project... we can now unset
        // the project and save some memory space, we won't be utilising the project beyond this point as we will have
        // it attached onto (task).
        unset($project);

        // acquire all the users for this project, so we are able to add this to the watchers, and the assignee
        // of the project, we are only going to want to reference users that are within this project spec, and only
        // those that have been granted permission to view will be showing up within the task additions.
        $task->project_users = $task->project->user_contributors->map(function ($project_user) {
            return $project_user->user;
        });

        // Getting the Task Priorities, statuses and issue types for the task so that we're able to start piecing
        // together the components for what a task may need.
        $task_priorities  = TaskPriority::all();
        $task_statuses    = TaskStatus::all();
        $task_issue_types = TaskIssueType::all();

        // Acquire all the task time logging, along with the total amount of time logged, this will be grabbing all time
        // logging of all users that has ever placed against the selected task.
        $task->task_time_logs    = TimeLogRepository::sortTaskTimeLogs($task);
        $task->total_time_logged = TimeLogRepository::getTotalTimeLogged($task);

        $this->vs->set('title', " - Task - {$task->name}")
                 ->set('current_page', 'page.projects.tasks.list');

        return view('task.view_task', compact(
            'task',
            'task_priorities',
            'task_statuses',
            'task_issue_types'
        ));
    }

    /**
    * this method is enabling the user to be able to search for a particular set of tasks... this will look for tasks
    * that have the name of the passed variable: [name]. this method will also be taking a number and will try find
    * tasks that are matching in that task id passed... if i search for 1, then we want task (1, 10, 11, 111, 1111)
    * etc.
    *
    * @param Request $request
    * @return string
    */
    public function _ajaxSearchTasksGet(Request $request): string
    {
        $name = $request->input('name');

        $tasks = Task::query()
            ->with('project')
            ->whereRaw("name like '%$name%'")
            ->orWhere('id', '=', $name)
            ->get();

        return $tasks->isNotEmpty() ? TaskRepository::displayTaskDropdownSearch($tasks) : '';
    }

    /**
    * This method simply collects all the tasks that are passed, we build up an array of filters that will be passed
    * to the task repository which will get the task data and assort the data based on the passed filters that we have
    * passed through to the system
    *
    * @param Request $request
    * @return JsonResponse
    * @throws Throwable
    */
    public function _ajaxViewTasksGet(Request $request): JsonResponse
    {
        $filters = (object) [
            'project_id'       => (int) $request->input('project_id'),
            'task_statuses'    => $request->input('task_statuses'),
            'task_issue_types' => $request->input('task_issue_types'),
            'task_priorities'  => $request->input('task_priorities'),
            'search'           => (string) $request->input('search'),
            'pagination'       => $request->input('pagination'),
            'tasks_per_page'   => (integer) $request->input('tasks_per_page')
        ];

        $project_setting = ProjectSetting::query()
            ->where('project_id', '=', $filters->project_id)
            ->first();

        if ($project_setting instanceof ProjectSetting && $project_setting->view_id === 2)
            $filters->pagination = false;

        // we are just going to be gathering all the filters, and shoving them off to the task repository get tasks
        // method so that we can simply return a collection of tasks...
        $tasks = TaskRepository::getTasks($filters);

        return $project_setting instanceof ProjectSetting &&
               $project_setting->view_id === 2
                    ? TaskRepository::boardView($tasks)
                    : TaskRepository::listView($tasks);
    }

    /**
    * This method will return a modal to the frontend so that the user is able to actually create a task... this will
    * just take a basic request as a parameter and nothing else; this will spit back to the user a form of everything
    * that a task will need in order to be created...
    *
    * @param Request $request
    * @return Factory|View
    */
    public function _ajaxViewCreateTaskGet(Request $request): Factory|View
    {
        $projects = Project::where('user_id', '=', Auth::id())->get();
        return view('task.modals.view_make_task_modal', compact(
            'projects'
        ));
    }

    /**
    * Method for creating tasks in the system, this will essentially take the bare necessities in order for making a
    * task, however there is a possibility that the user may want to create a fully fledged task with everything
    * specced out in which we will be giving the user the ability to simplify create oen and then come back to the
    * default later.
    *
    * @param Request $request
    * @return JsonResponse
    */
    public function _ajaxCreateTaskPost(Request $request): JsonResponse
    {
        $task = Task::query()->create([
            'project_id'       => (int) $request->input('project_id'),
            'user_id'          => Auth::id(),
            'name'             => $request->input('name'),
            'description'      => $request->input('description'),
            'assigned_user_id' => null,
            'task_status_id'   => TaskStatus::$TYPE_TODO,
            'task_priority_id' => 1 ,
            'due_date'         => null
        ]);

        $task->log(TaskLog::TASK_MAKE, $task->name);

        $task->project->project_setting->increment('tasks_total');
        $task->project->project_setting->increment('tasks_in_todo');

        return response()->json([
           'response' => 'Task has been successfully created'
        ]);
    }

    /**
    * This particular method will be a globalised method which handles all the editing of tasks (singular) to which
    * will return a Json response so that the javascript can alert the user that something has happened. this method
    * will take simply a field, and a value. (nothing specific) so that this can be used universally across all updates
    * to a task.
    *
    * @param Request $request
    * @return JsonResponse
    */
    public function _ajaxEditTaskPost(Request $request): JsonResponse
    {
        // todo update this into the method parameter, allowing for model route binding; alleviating the need for a
        //      custom query to the database.
        $task_id    = (int) $request->input('task_id');
        $field      = $request->input('field');
        $value      = $request->input('value');

        /** @var Task $task */
        $task = Task::query()
            ->select('*')
            ->with([
                'project',
                'project.project_setting',
                'task_status'
            ])
            ->where('id', '=', $task_id)
            ->first();

        // if the task status id is the field that we are going to be editing, the numbers that reside against the
        // project for the number of in to do / in progress / in archive etc.
        if ($field === 'task_status_id') {
            ProjectSettingRepository::updateProjectSettingStatusStatistics(
                $task->project->project_setting,
                $value,
                $task->$field
            );
        }

        // check which field we are working with in order to decide which constant we are going to be pushing into the
        // task log specifically.
        $task_log_constant_variable = 'TASK_' . str_replace(['TASK_', '_ID'], '', mb_strtoupper($field));
        $task_log_constant = constant("App\Models\Task\TaskLog::{$task_log_constant_variable}");

        // Preparing the log information for this task edit... the log type will be decided in the switch case; seeing
        // as this is a global method for updating tasks.
        $task->log($task_log_constant, $value, $task->$field);

        // after we have finished logging the particular change to the task, we are now able to update the item in the
        // database.
        $task->update([ $field => $value ]);

        // if the application has the pusher enabled; then we are just going to update the task; passing in the task
        // name, as well as the value that it's getting updated to. (this will just send a push notification utilising
        // web sockets)
        if (env('PUSHER_ENABLED')) {
            event(new TaskMessage(
                Auth::user()->getFullName()
                . 'is updating'
                . "{$task->id} {$task->name}", $task, $field
            ));
        }

        return response()->json([ 'response' => 'You have updated the task...' ]);
    }

    /**
    * This method is for deleting tasks itself; once we have no use for a task in the system, whether that's because of
    * duplication, or mistake in the creation process and or it's no longer desired to be there, then we might as well
    * just remove the task altogether from the system (which will allow for saving space)
    *
    * @param Request $request
    * @param $project_code
    * @param $task_id
    * @return RedirectResponse
    */
    public function _deleteTaskGet(Request $request, $project_code, $task_id): RedirectResponse
    {
        $project = Project::query()
            ->select('*')
            ->where('code', '=', $project_code)
            ->where('user_id', '=', $this->vs->get('user')->id)
            ->with([
                // we just want to be acquiring the task that we have passed through, but we want to make sure that it
                // is coming from the right user signed in, meaning that we should only be deleting a very particular
                // task that resides against the authenticated user.
                'tasks' => function ($query) use ($task_id) {
                    $query->where('id', '=', $task_id);
                },
                'project_setting'
            ])->first();

        // if we don't have access to the project in question, aka, if this project exists, or the user doesn't have
        // permission to view, like wise, if the task they're looking at doesn't exist, or has no permission, then
        // we're going to return, for now.
        if (! $project instanceof Project || ! ($task = $project->tasks->first()) instanceof Task)
            return back();

        // when we delete a task, we are going to want to decrement where this task came from; and what status this
        // task status this was sitting in so that we can decide to show a true amount of numbers.
        if ($project->project_setting->{$decrement_statistic = ProjectSetting::$tasks_in[$task->task_status_id]} > 0)
            $project->project_setting->decrement($decrement_statistic);

        // delete this particular task...
        $project->tasks->first()->delete();

        // decrement the total number of tasks when the task has been deleted, as we now have one task less to deal
        // with, and then we're now in a (x) number of tasks after it has been deleted.
        $project->project_setting->decrement('tasks_total');

        return redirect()->action($project->getUrl());
    }
}