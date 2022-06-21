<?php

namespace App\Http\Controllers\Project\Task;

use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Task\TaskLog;
use Illuminate\Http\JsonResponse;
use App\Models\Task\TaskChecklist;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\Factory;
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
        $task_id    = (int) $request->input('task_id');
        $project_id = (int) $request->input('project_id');

        $task_checklists = TaskChecklistRepository::sortTaskChecklistProgress(
            TaskChecklist::select('*')
                ->with(['task_checklist_items'])
                ->where('task_id', '=', $task_id)
                ->where('project_id', '=', $project_id)
                ->where('user_id', '=', Auth::id())
                ->orderBy('order')
                ->get()
        );

        return view('library.task.task_checklists.view_task_checklists', compact(
            'task_checklists'
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
        $order               =    (int) $request->input('order');
        $task_id             =    (int) $request->input('task_id');
        $project_id          =    (int) $request->input('project_id');
        $task_checklist_name = (string) $request->input('task_checklist_name');

        $task_checklist = TaskChecklist::create([
            'project_id' => $project_id,
            'task_id'    => $task_id,
            'user_id'    => Auth::id(),
            'name'       => $task_checklist_name,
            'order'      => $order
        ]);

        $task_checklist->log(TaskLog::TASK_CHECKLIST_MAKE, $task_checklist->name);

        return response()->json([
            'response'          => 'Successfully created checklist',
            'task_checklist_id' => $task_checklist->id
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
        $name              = (string) $request->input('name');
        $task_checklist_id = (int) $request->input('task_checklist_id');

        // acquire the task checklist that we are about to update, make sure this entry exists prior to doing anything
        // with it, and then checking if the user in question is able to do anything regarding this particular entry.
        $task_checklist = TaskChecklist::where('id', '=', $task_checklist_id)->first();

        // log the specific change that the user has attempted to make, this will be taking the constant of task
        // checklist name (7) as the update type for the log.
        $task_checklist->log(TaskLog::TASK_CHECKLIST_NAME, $name, $task_checklist->name);

        // after we are satisfied that the log has been met, then we are going to actually update the task checklist
        // entry in the database
        $task_checklist->update([
            'name' => $name
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
        $is_zipped         = $request->input('is_zipped');

        $task_checklist_id = (int) $request->input('task_checklist_id');

        $task_checklist    = TaskChecklist::where('id', '=', $task_checklist_id)->first();

        $task_checklist->update([
            'is_zipped' => $is_zipped
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
        $task_checklists = $request->input('task_checklists');
        if (count($task_checklists) > 0) {
            DB::transaction(function () use ($task_checklists) {
                foreach ($task_checklists as $task_checklist) {
                    DB::table('task_checklist')
                        ->where('id', '=', $task_checklist['task_checklist_id'])
                        ->update([
                            'order' => $task_checklist['order'],
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
        $task_checklist_id = (int) $request->input('task_checklist_id');
        $task_id           = (int) $request->input('task_id');
        $project_id        = (int) $request->input('project_id');

        $task_checklist = TaskChecklist::with('task_checklist_items')
            ->where('id', '=', $task_checklist_id)
            ->where('task_id', '=', $task_id)
            ->where('project_id', '=', $project_id)
            ->where('user_id', '=', Auth::id())
            ->first();

        // delete the task checklist in question, when this happens, this is going to cascade to its children, and
        // delete all the checklist items that have the checklist item.s
        $task_checklist->delete();

        // we are making the assumption that there was a checklist to delete, and we are going to be alerting the user
        // regardless that a checklist has been deleted.
        return response()->json([
            'response' => 'Checklist has been deleted'
        ]);
    }
}
