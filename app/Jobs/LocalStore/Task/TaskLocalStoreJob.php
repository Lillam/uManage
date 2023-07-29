<?php

namespace App\Jobs\LocalStore\Task;

use App\Models\Task\Task;
use Illuminate\Support\Collection;
use App\Jobs\LocalStore\LocalStoreJob;

class TaskLocalStoreJob extends LocalStoreJob
{
    /**
     * @var array|Collection
     */
    private array|Collection $tasks;

    /**
    * Create a new job instance.
    *
    * TaskLocalStoreJob constructor.
    *
    * @return void
    */
    public function __construct(?string $destination = 'tasks/tasks.json')
    {
        $this->setDestination($destination);

        $this->tasks = Task::query()
            ->select('*')
            ->with([
                'taskComments',
                'taskChecklists',
                'taskChecklists.taskChecklistItems',
                'taskTimeLogs'
            ])
            ->get();
    }

    /**
    * Execute the job.
    *
    * @return void
    */
    public function handle(): void
    {
        $tc   = 'task_checklists';
        $tci  = 'task_checklist_items';
        $tcom = 'task_comments';
        $ttl  = 'task_time_logs';

        foreach ($this->tasks as $task) {
            $this->put[$task->id] = [
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


            foreach ($task->taskComments as $taskComment) {
                $this->put[$task->id][$tcom][$taskComment->id] = [
                    'id'         => $taskComment->id,
                    'project_id' => $task->project_id,
                    'task_id'    => $taskComment->task_id,
                    'user_id'    => $taskComment->user_id,
                    'parent_id'  => $taskComment->parent_id,
                    'content'    => $taskComment->content
                ];
            }

            foreach ($task->taskTimeLogs as $taskTimeLog) {
                $this->put[$task->id][$ttl][$taskTimeLog->id] = [
                    'id'         => $taskTimeLog->id,
                    'task_id'    => $taskTimeLog->task_id,
                    'user_id'    => $taskTimeLog->user_id,
                    'project_id' => $taskTimeLog->project_id,
                    'note'       => $taskTimeLog->note,
                    'from'       => $taskTimeLog->from,
                    'to'         => $taskTimeLog->to,
                    'time_spent' => $taskTimeLog->time_spent
                ];
            }

            foreach ($task->taskChecklists as $taskChecklist) {
                $this->put[$task->id][$tc][$taskChecklist->id] = [
                    'id'         => $taskChecklist->id,
                    'task_id'    => $taskChecklist->task_id,
                    'project_id' => $task->project_id,
                    'user_id'    => $task->user_id,
                    'name'       => $taskChecklist->name,
                    'order'      => $taskChecklist->order
                ];

                foreach ($taskChecklist->taskChecklistItems as $taskChecklistItem) {
                    $this->put[$task->id][$tc][$taskChecklist->id][$tci][$taskChecklistItem->id] = [
                        'id'                => $taskChecklistItem->id,
                        'task_checklist_id' => $taskChecklistItem->task_checklist_id,
                        'task_id'           => $task->id,
                        'project_id'        => $task->project_id,
                        'user_id'           => $task->user_id,
                        'name'              => $taskChecklistItem->name,
                        'order'             => $taskChecklistItem->order,
                        'is_checked'        => $taskChecklistItem->is_checked
                    ];
                }
            }
        }

        // after getting all the results, turn the entire collection into a json object and store it into a file. this
        // will be stored inside the local storage; public/storage/tasks/tasks.json?
        $this->putToStorage('local');
    }
}
