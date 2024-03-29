<?php

namespace App\Http\Controllers\Web\Project\Task;

use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Task\TaskLog;
use Illuminate\Http\JsonResponse;
use App\Models\Task\TaskChecklist;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\Factory;
use App\Http\Controllers\Web\Controller;
use App\Repositories\Task\TaskChecklistRepository;

class TaskChecklistController extends Controller
{
    /**
    * This particular method is for viewing the checklists of a task. A task id will be passed through the javascript
    * ajax method which will look for all checklists that relate to this parameter set and return the entire checklist
    * with the necessary checklist items.
    *
    * @param Request $request
    * @return Factory|View
    */
    public function _ajaxViewTaskChecklistsGet(Request $request): Factory|View
    {
        $taskChecklists = TaskChecklistRepository::sortTaskChecklistProgress(
            TaskChecklist::query()
                ->select('*')
                ->with(['taskChecklistItems'])
                ->where('task_id', '=', $request->input('task_id'))
                ->where('project_id', '=', $request->input('project_id'))
                ->where('user_id', '=', Auth::id())
                ->orderBy('order')
                ->get()
        );

        return view('library.task.task_checklists.view_task_checklists', compact(
            'taskChecklists'
        ));
    }

    /**
    * This method is for creating new checklists, the user will fire up a name. this method will also return a json
    * response to the user so that the javascript can process and render the visuals and alert the user that a
    * checklist has been edited, and pass in the checklist id of the element that has just been created.
    *
    * @param Request $request
    * @return JsonResponse
    */
    public function _ajaxMakeTaskChecklistPost(Request $request): JsonResponse
    {
        $taskChecklist = TaskChecklist::create([
            'project_id' => $request->input('project_id'),
            'task_id'    => $request->input('task_id'),
            'user_id'    => Auth::id(),
            'name'       => $request->input('task_checklist_name'),
            'order'      => $request->input('order')
        ]);

        $taskChecklist->log(TaskLog::TASK_CHECKLIST_MAKE, $taskChecklist->name);

        return response()->json([
            'response'          => 'Successfully created checklist',
            'task_checklist_id' => $taskChecklist->id
        ]);
    }

    /**
    * This method is for handling the posting data of checklists... any edits that can be made towards a checklist will
    * be processed here. this will also return a response to the user in json so that the javascript can render it
    * to the user and alert that something has been changed in the database.
    *
    * @param Request $request
    * @return JsonResponse
    */
    public function _ajaxEditTaskChecklistPost(Request $request): JsonResponse
    {
        // acquire the task checklist that we are about to update, make sure this entry exists prior to doing anything
        // with it, and then checking if the user in question is able to do anything regarding this particular entry.
        $taskChecklist = TaskChecklist::query()->where('id', '=', $request->input('task_checklist_id'))->first();

        // log the specific change that the user has attempted to make, this will be taking the constant of task
        // checklist name (7) as the update type for the log.
        $taskChecklist->log(TaskLog::TASK_CHECKLIST_NAME, $request->input('name'), $taskChecklist->name);

        // after we are satisfied that the log has been met, then we are going to actually update the task checklist
        // entry in the database
        $taskChecklist->update([
            'name' => $request->input('name')
        ]);

        return response()->json([
            'response' => 'Checklist has been updated'
        ]);
    }

    /**
    * @param Request $request
    * @return JsonResponse
    */
    public function _ajaxEditTaskChecklistEditZipStatus(Request $request): JsonResponse
    {
        $taskChecklist    = TaskChecklist::query()->where('id', '=', $request->input('task_checklist_id'))->first();

        $taskChecklist->update([
            'is_zipped' => $request->input('is_zipped')
        ]);

        return response()->json([
            'response' => 'Checklist has been zipped'
        ]);
    }

    /**
    * This method is entirely for altering the checklist's ordering. the system will be pushing up a list of checklists
    * with their id and order in an associated array.
    * [1 => 1, 12 => 2, 13 => 3, 14 => 4] etc.
    *
    * @param Request $request
    * @return JsonResponse
    */
    public function _ajaxEditTaskChecklistOrderPost(Request $request): JsonResponse
    {
        $taskChecklists = $request->input('task_checklists');

        if (count($taskChecklists) > 0) {
            DB::transaction(function () use ($taskChecklists) {
                foreach ($taskChecklists as $taskChecklist) {
                    DB::table('task_checklist')
                        ->where('id', '=', $taskChecklist['task_checklist_id'])
                        ->update([
                            'order' => $taskChecklist['order'],
                            'updated_at' => Carbon::now()
                        ]);
                }
            });

            // once we have collected all the update queries into a transaction, we are going to be simultaneously
            // running these from one transaction rather than progressively updating them in separate connection
            // queries.
            DB::commit();

            // return to the user that the checklists have been updated accordingly, this will be returned in json
            // format so that the frontend javascript can put this data into an alert.
            return response()->json([
                'response' => 'Items have been successfully reordered'
            ]);
        }

        // we shouldn't ever really make it here, however, if the user has somehow managed to make a request where no
        // checklists are there to update, then we are going to be alerting the user that they have tried to make some
        // updates against checklists that aren't there...
        return response()->json([
            'response' => 'No items were received to update'
        ]);
    }

    /**
    * This method is simply just allowing the user to send off a request which will delete the checklist. because of
    * the constraint this method will delete all and any checklist items that are sitting inside this checklist group
    * which will need to be taken into consideration. This will also return a json response alert to the user so that
    * the javascript can process the event and alert whether it was a success or not...
    *
    * @param Request $request
    * @return JsonResponse
    */
    public function _ajaxDeleteTaskChecklistPost(Request $request):JsonResponse
    {
        $taskChecklist = TaskChecklist::query()
            ->with('taskChecklistItems')
            ->where('id', '=',  $request->input('task_checklist_id'))
            ->where('task_id', '=', $request->input('task_id'))
            ->where('project_id', '=', $request->input('project_id'))
            ->where('user_id', '=', Auth::id())
            ->first();

        // delete the task checklist in question, when this happens, this is going to cascade to its children, and
        // delete all the checklist items that have the checklist items
        $taskChecklist->delete();

        // we are making the assumption that there was a checklist to delete, and we are going to be alerting the user
        // regardless that a checklist has been deleted.
        return response()->json([
            'response' => 'Checklist has been deleted'
        ]);
    }
}
