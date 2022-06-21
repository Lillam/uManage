<?php

namespace App\Http\Controllers\Project\Task;

use Throwable;
use App\Models\Task\Task;
use App\Models\Task\TaskLog;
use Illuminate\Http\Request;
use App\Models\Task\TaskComment;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class TaskCommentController extends Controller
{
    /**
    * This method will be viewing the task comments via ajax, this will be called on initial load of the task page...
    * so we are offsetting the initial page load. this also gives us the ability to refresh the section should we need
    * to. This method will be requiring a task_id by default, so that we can grab the current existing comments against
    * the task in question.
    *
    * @param Request $request
    * @return JsonResponse
    * @throws Throwable
    */
    public function _ajaxViewTaskCommentsGet(Request $request): JsonResponse
    {
        $task_id    = (int) $request->input('task_id');
        $project_id = (int) $request->input('project_id');
        $page       = (int) $request->input('page') ?: 1;
        $limit      = 5;
        $offset     = $limit * ($page - 1);

        // let's grab the task that we are going to be attaching a comment to, we are going to need to see if this
        // particular task in question exists for us to connect this comment to, if it does proceed, if it does not
        // we are going to want to end here...
        $task = Task::query()
            ->where('id', '=', $task_id)
            ->where('project_id', '=', $project_id)
            ->where('user_id', '=', Auth::id())
            ->first();

        $task_comments_count = TaskComment::query()
            ->where('task_id', '=', $task_id)
            ->where('project_id', '=', $project_id)
            ->where('user_id', '=', Auth::id())
            ->count();

        // acquire the total number of pages that we are going to be able to show, this will be needed to see if we can
        // go left or right.
        $total_pages = ceil($task_comments_count / $limit);

        // acquire all the task comments that are for this particular task, this will return every comment that exists
        // with the passed task id.
        $task_comments = TaskComment::where('task_id', '=', $task_id)
            ->where('project_id', '=', $project_id)
            ->where('user_id', '=', Auth::id())
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->offset($offset)
            ->get();

        return response()->json([
            'html' => view('library.task.task_comments.view_task_comments', compact(
                'task_comments',
                'task',
                'page',
                'total_pages'
            ))->render()
        ]);
    }

    /**
    * This method is entirely for submitting the ajax post request in order for making a task comment. this method will
    * be requiring a task id by default, as this particular method is utilised for connecting a comment to a task.
    *
    * @param Request $request
    * @return JsonResponse
    */
    public function _ajaxMakeTaskCommentPost(Request $request): JsonResponse
    {
        $task_id    = $request->input('task_id');
        $project_id = $request->input('project_id');
        $content    = $request->input('content');

        $comment = TaskComment::query()
            ->create([
                'task_id'    => $task_id,
                'project_id' => $project_id,
                'user_id'    => $this->vs->get('user')->id,
                'content'    => $content
            ]);

        $comment->log(TaskLog::TASK_COMMENT);

        return response()->json([
            'response' => 'Comment has been successfully been created',
            'comment'  => [
                'content' => $comment->content,
                'created_at' => $comment->created_at
            ],
            'user'     => [
                'name'     => Auth::user()->getFullName(),
                'initials' => Auth::user()->getInitials()
            ],
        ]);
    }

    /**
    * This method allows the user to delete a particular task comment, this method will be returning json so that the
    * user will be alerted in kind on the frontend instantly.
    *
    * @param Request $request
    * @return JsonResponse
    */
    public function _ajaxDeleteTaskCommentPost(Request $request): JsonResponse
    {
        TaskComment::query()
            ->where('id', '=', $request->input('task_comment_id'))
            ->delete();

        return response()->json([
            'response' => 'Successfully deleted comment'
        ]);
    }
}
