<?php

namespace App\Http\Controllers\Web\Project\Task;

use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Task\TaskLog;
use App\Helpers\Text\TextHelper;
use Illuminate\Http\JsonResponse;
use App\Models\Task\TaskChecklist;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Task\TaskChecklistItem;
use Illuminate\Contracts\View\Factory;
use App\Http\Controllers\Web\Controller;

class TaskChecklistItemController extends Controller
{
    /**
    * this method is for creating checklist items, this will be handling the post request which will have been sent via
    * ajax.
    *
    * @param Request $request
    * @return JsonResponse
    */
    public function _ajaxMakeTaskChecklistItemPost(Request $request): JsonResponse
    {
        // Store the checklist item temporary just in case we are going to want to reference what is happening
        // with this checklist item in question, we can report back to the user that the checklist item name has been
        // created, within the json response below.
        $taskChecklistItem = TaskChecklistItem::create([
            'task_checklist_id' => $request->input('task_checklist_id'),
            'project_id'        => $request->input('project_id'),
            'task_id'           => $request->input('task_id'),
            'user_id'           => Auth::id(),
            'name'              => TextHelper::safeTags($request->input('task_checklist_item_name')),
            'order'             => $request->input('order')
        ]);

        // create a log of the checklist item that has been made.
        $taskChecklistItem->log(TaskLog::TASK_CHECKLIST_ITEM_MAKE, $taskChecklistItem->name);

        // if the checklist has been zipped up, but we have added a new item, then we are going to unzip the checklist
        // so that the user is in knowledge of the new checklist item that has been added to the checklist group.
        $task_checklist = TaskChecklist::query()->where('id', '=', $request->input('task_checklist_id'))->first();
        // @todo this piece is completely unnecessary to simply achieve that is the invert; so we can flip the boolean
        // of is_zipped and show that to be what the new value is.
        $task_checklist_unzipped = false;

        if ($task_checklist->is_zipped === true) {
            $task_checklist->is_zipped = false;
            $task_checklist_unzipped = true;
            $task_checklist->save();
        }

        return response()->json([
            'response'                => 'You have successfully created a checklist item',
            'task_checklist_unzipped' => $task_checklist_unzipped
        ]);
    }

    /**
    * This particular method is a point of optimisation for returning a subset of checklist items based on a checklist
    * id we are passing through. rather than returning the entire checklist groups over and over we are just returning
    * the checklist in question's checklist items to give a visual representation of refreshing.
    *
    * @param Request $request
    * @return Factory|View
    */
    public function _ajaxViewTaskChecklistItemsGet(Request $request): Factory|View
    {
        $taskChecklistItems = TaskChecklistItem::query()
            ->where('task_checklist_id', '=', $request->input('task_checklist_id'))
            ->where('task_id', '=', $request->input('task_id'))
            ->where('project_id', '=', $request->input('project_id'))
            ->where('user_id', '=', Auth::id())
            ->orderBy('order')
            ->get();

        return view('library.task.task_checklist_items.view_task_checklist_items', compact(
            'taskChecklistItems'
        ));
    }

    /**
    * This method is for editing task checklist items and handling the posting of the data that the user is
    * interacting with the task checklist item... if the task checklist cannot be found, then this method will return
    * nothing otherwise this method will return a response alerting the user that a change has been made (returned in
    * json)
    *
    * @param Request $request
    * @return JsonResponse
    */
    public function _ajaxEditTaskChecklistItemPost(Request $request): JsonResponse
    {
        $data = [];

        $task_checklist_item_id =    (int) $request->input('task_checklist_item_id');
        $name                   = (string) $request->input('name');
        $checked                = $request->input('is_checked');
        $is_checked             = $checked === 'false' ? 0 : 1;

        $taskChecklistItem = TaskChecklistItem::query()
            ->where('id', '=', $task_checklist_item_id)
            ->first();

        // if the user has opted to send a name update request, then we are going to be inserting this into the data
        // associate array, which will be $data['name'] = 'something';
        if (! empty($name) && $name !== '') {
            $data['name'] = $new = TextHelper::stripAttributes($name);
            $old = $taskChecklistItem->name;
            $taskChecklistItem->log(TaskLog::TASK_CHECKLIST_ITEM_NAME, $new, $old);
        }

        // because of the way that booleans are passed through to laravel from javascript, we are needing to check
        // is the checked prop "true" or "false", if it is false, then we are going to be updating is_checked to 0,
        // otherwise we should assume we have something, thus we are setting it to 1.
        if (! empty($checked) && ($is_checked === 0 || $is_checked === 1)) {
            $data['is_checked'] = $new = $is_checked;
            $old = $taskChecklistItem->is_checked;
            $taskChecklistItem->log(TaskLog::TASK_CHECKLIST_ITEM_CHECKED, $new, $old);
        }

        // update the particular checklist item with the necessary data that will have been built above, this bas been
        // done in the way that this method can be utilised for any form of update providing that we are sending the
        // necessary data to update a checklist item.
        $taskChecklistItem->update($data);

        return response()->json([
            'response' => 'Checklist item has been updated'
        ]);
    }

    /**
    * This method will simply be deleting the checklist item from the database, the method will return a json response
    * so that the javascript can alert the user that something has happened.
    *
    * @param Request $request
    * @return JsonResponse
    */
    public function _ajaxDeleteTaskChecklistItemPost(Request $request): JsonResponse
    {
        $task_checklist_item_id = (int) $request->input('task_checklist_item_id');
        $task_id                = (int) $request->input('task_id');
        $project_id             = (int) $request->input('project_id');

        // acquire the task checklist item so that we are able to create a log entry prior to deleting the item from
        // the database.
        $taskChecklistItem = TaskChecklistItem::query()
            ->where('id', '=', $task_checklist_item_id)
            ->where('project_id', '=', $project_id)
            ->where('task_id', '=', $task_id)
            ->where('user_id', '=', Auth::id())
            ->first();

        // after we have finished with the task checklist item logging, we are now safe to delete the particular
        // entry from the database.
        $taskChecklistItem->delete();

        // we are making an assumption that the checklist item existed to begin with, however if this was pushed up and
        // the checklist item was not found for this, then one way or another we are going to be 'deleted' something and
        // confirming to the user that the checklist item has been deleted, we will be returning this as json so that the
        // frontend javascript can render some visual confirmation (ajax)
        return response()->json([
            'response' => 'Checklist item has been deleted'
        ]);
    }

    /**
    * This method is for handling the posting of the Task Checklist item ordering... this will fire up an array of
    * checklist item ids [1 => 1, 2 => 2, 4 => 3, 3 => 4] etc. and run through this into a transaction that the system
    * will be able to execute as one request as opposed to (x) number of task checklist items... This method will also
    * be returning a json response so that the user can have an instant feedback that something has happened.
    *
    * @param Request $request
    * @return JsonResponse
    */
    public function _ajaxEditTaskChecklistItemOrderPost(Request $request): JsonResponse
    {
        $taskChecklistItems = $request->input('task_checklist_items');

        if (count($taskChecklistItems) > 0) {
            DB::transaction(function () use ($taskChecklistItems) {
                foreach ($taskChecklistItems as $taskChecklistItem) {
                    DB::table('task_checklist_item')
                        ->where('id', '=', $taskChecklistItem['task_checklist_item_id'])
                        ->where('task_id', '=', $taskChecklistItem['task_id'])
                        ->where('project_id', '=', $taskChecklistItem['project_id'])
                        ->where('user_id', '=', Auth::id())
                        ->update([
                            'order'             => $taskChecklistItem['order'],
                            'task_checklist_id' => $taskChecklistItem['task_checklist_id'],
                            'updated_at'        => Carbon::now()
                        ]);
                }
            });

            // commit the transaction, all the above rows will be inserted into one call.
            DB::commit();

            // once we have committed the above changes to the database, we are going to want to alert the user that the
            // changes have been made successfully... we are going to be returning this as a json object so that the
            // javascript can better handle the content.
            return response()->json([ 'response' => 'Items have been successfully reordered' ]);
        }

        // if we have made it here, then we have somehow managed to push a variety of checklist items to the system that
        // did not need updating, however, we are still going to want to greet the user with some form of response, so
        // the user knows that something has happened, we are going to be returning this response as json so that the
        // frontend javascript can handle the visuals.
        return response()->json([ 'response' => 'No items were received to update' ]);
    }
}
