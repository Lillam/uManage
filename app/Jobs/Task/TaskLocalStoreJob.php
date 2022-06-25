<?php

namespace App\Jobs\Task;

use App\Models\Task\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class TaskLocalStoreJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var
     */
    private $tasks;

    /**
    * @var array
    */
    private array $put_tasks;

    /**
    * Create a new job instance.
    *
    * TaskLocalStoreJob constructor.
    *
    * @return void
    */
    public function __construct()
    {
        $this->tasks = Task::select('*')->with([
            'task_comments',
            'task_checklists',
            'task_checklists.task_checklist_items',
            'task_time_logs'
        ])->get();
    }

    /**
    * Execute the job.
    *
    * @return void
    */
    public function handle()
    {
        $tc   = 'task_checklists';
        $tci  = 'task_checklist_items';
        $tcom = 'task_comments';
        $ttl   = 'task_time_logs';

        foreach ($this->tasks as $task) {
            $this->put_tasks[$task->id] = [
                'project_id'         => $task->project_id,
                'id'                 => $task->id,
                'user_id'            => $task->task_id,
                'assigned_user_id'   => $task->assigned_user_id,
                'task_issue_type_id' => $task->task_issue_type_id,
                'task_status_id'     => $task->task_status_id,
                'task_priority_id'   => $task->task_priority_id,
                'name'               => $task->name,
                'description'        => $task->description,
                'due_date'           => $task->due_date
            ];

            if ($task->task_comments->isNotEmpty()) {
                foreach ($task->task_comments as $task_comment) {
                    $this->put_tasks[$task->id][$tcom][$task_comment->id] = [
                        'id'         => $task_comment->id,
                        'project_id' => $task->project_id,
                        'task_id'    => $task_comment->task_id,
                        'user_id'    => $task_comment->user_id,
                        'parent_id'  => $task_comment->parent_id,
                        'content'    => $task_comment->content
                    ];
                }
            }

            if ($task->task_time_logs->isNotEmpty()) {
                foreach ($task->task_time_logs as $task_time_log) {
                    $this->put_tasks[$task->id][$ttl][$task_time_log->id] = [
                        'id'         => $task_time_log->id,
                        'task_id'    => $task_time_log->task_id,
                        'user_id'    => $task_time_log->user_id,
                        'project_id' => $task_time_log->project_id,
                        'note'       => $task_time_log->note,
                        'from'       => $task_time_log->from,
                        'to'         => $task_time_log->to,
                        'time_spent' => $task_time_log->time_spent
                    ];
                }
            }

            if ($task->task_checklists->isNotEmpty()) {
                foreach ($task->task_checklists as $task_checklist) {
                    $this->put_tasks[$task->id][$tc][$task_checklist->id] = [
                        'id'         => $task_checklist->id,
                        'task_id'    => $task_checklist->task_id,
                        'project_id' => $task->project_id,
                        'user_id'    => $task->user_id,
                        'name'       => $task_checklist->name,
                        'order'      => $task_checklist->order
                    ];

                    if ($task_checklist->task_checklist_items->isNotEmpty()) {
                        foreach ($task_checklist->task_checklist_items as $task_checklist_item) {
                            $this->put_tasks[$task->id][$tc][$task_checklist->id][$tci][$task_checklist_item->id] = [
                                'id'                => $task_checklist_item->id,
                                'task_checklist_id' => $task_checklist_item->task_checklist_id,
                                'task_id'           => $task->id,
                                'project_id'        => $task->project_id,
                                'user_id'           => $task->user_id,
                                'name'              => $task_checklist_item->name,
                                'order'             => $task_checklist_item->order,
                                'is_checked'        => $task_checklist_item->is_checked
                            ];
                        }
                    }
                }
            }
        }

        // after getting all of the results, turn the entire collection into a json object and store it into a file.
        // this will be stored inside the local storage; public/storage/tasks/tasks.json?
        Storage::disk('local')->put(
            'tasks/tasks.json',
            json_encode($this->put_tasks)
        );
    }
}
