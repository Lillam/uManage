<?php

namespace Database\Seeders;

use Exception;
use App\Models\Task\Task;
use App\Models\TimeLog\TimeLog;
use Illuminate\Database\Seeder;
use App\Models\Task\TaskComment;
use Illuminate\Support\Facades\DB;
use App\Models\Task\TaskChecklist;
use App\Models\Project\ProjectSetting;
use App\Models\Task\TaskChecklistItem;
use Illuminate\Support\Facades\Storage;

class TaskSeeder extends Seeder
{
    /**
    * Run the database seeders.
    *
    * @return void
    */
    public function run(): void
    {
        if (! Storage::disk('local')->exists('tasks/tasks.json')) {
            return;
        }

        $this->process(collect(
            json_decode(Storage::disk('local')->get('tasks/tasks.json'))
        ));
    }

    /**
    * @param $tasks
    * @return void
    */
    public function process($tasks): void
    {
        DB::transaction(function () use ($tasks) {
            // when we are going to seed the tasks in the system; we are just going to update all the project settings
            // that these tasks belong to; and update the total and completed tasks to 0 as these will just increment up
            // when we want them to be back at 0; before the re-seeding happens.
            DB::table('project_setting')->update([
                'tasks_total' => 0,
                'tasks_in_completed' => 0,
                'tasks_in_todo'      => 0,
                'tasks_in_progress'  => 0,
                'tasks_in_archived'  => 0
            ]);

            $bar = $this->command->getOutput()->createProgressBar(count($tasks));

            foreach ($tasks as $taskId => $task) {
                try {
                    $theNewTask = Task::query()->updateOrCreate(['id' => $taskId], [
                        'id'                 => $taskId,
                        'project_id'         => $task->project_id,
                        'user_id'            => 1,
                        'name'               => $task->name,
                        'description'        => $task->description,
                        'task_issue_type_id' => $task->task_issue_type_id,
                        'assigned_user_id'   => $task->assigned_user_id,
                        'task_status_id'     => $task->task_status_id,
                        'task_priority_id'   => $task->task_priority_id,
                        'due_date'           => $task->due_date,
                    ]);
                } catch (Exception $exception) {
                    continue;
                }

                // finding which particular setting we're going to be incrementing, by acquiring it from the project
                // setting model, which will remove the need for having to do if this, if that, if this other thing and
                // doing this in a one liner.
                $theNewTask->project->projectSetting->increment(
                    ProjectSetting::$TASKS_IN[$theNewTask->task_status_id]
                );

                if (! empty($task->task_time_logs)) {
                    foreach ($task->task_time_logs as $taskTimeLogId => $taskTimeLog) {
                        TimeLog::query()->updateOrCreate(['id' => $taskTimeLogId], [
                            'id'         => $taskTimeLogId,
                            'task_id'    => $taskId,
                            'project_id' => $task->project_id,
                            'user_id'    => $taskTimeLog->user_id,
                            'note'       => $taskTimeLog->note,
                            'from'       => str_replace('T00:00:00.000000Z', '', $taskTimeLog->from),
                            'to'         => str_replace('T00:00:00.000000Z', '', $taskTimeLog->to),
                            'time_spent' => $taskTimeLog->time_spent
                        ]);
                    }
                }

                if (! empty($task->task_comments)) {
                    foreach ($task->task_comments as $taskCommentId => $taskComment) {
                        TaskComment::query()->updateOrCreate(['id' => $taskCommentId], [
                            'id'         => $taskCommentId,
                            'user_id'    => $taskComment->user_id,
                            'task_id'    => $taskId,
                            'project_id' => $task->project_id,
                            'parent_id'  => $taskComment->parent_id,
                            'content'    => $taskComment->content,
                        ]);
                    }
                }

                if (! empty($task->task_checklists)) {
                    foreach ($task->task_checklists as $taskChecklistId => $taskChecklist) {
                        TaskChecklist::query()->updateOrCreate(['id' => $taskChecklistId], [
                            'id'         => $taskChecklistId,
                            'task_id'    => $taskId,
                            'project_id' => $task->project_id,
                            'user_id'    => 1,
                            'name'       => $taskChecklist->name,
                            'order'      => $taskChecklist->order
                        ]);

                        if (! empty($taskChecklist->task_checklist_items)) {
                            foreach ($taskChecklist->task_checklist_items as $taskChecklistItemId
                                => $taskChecklistItem
                            ) {
                                TaskChecklistItem::query()->updateOrCreate(['id' => $taskChecklistItemId], [
                                    'id'                => $taskChecklistItemId,
                                    'task_checklist_id' => $taskChecklistId,
                                    'task_id'           => $taskId,
                                    'project_id'        => $task->project_id,
                                    'user_id'           => 1,
                                    'name'              => $taskChecklistItem->name,
                                    'order'             => $taskChecklistItem->order,
                                    'is_checked'        => $taskChecklistItem->is_checked
                                ]);
                            }
                        }
                    }
                }

                $theNewTask->project->projectSetting->increment('tasks_total');
                $bar->advance();
            }

            $bar->finish();
        });

        DB::commit();
    }
}
