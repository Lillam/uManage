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
        if (! Storage::disk('local')->exists('tasks/tasks.json'))
            return;

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

            foreach ($tasks as $task_id => $task) {
                try {
                    $the_new_task = Task::query()->updateOrCreate(['id' => $task_id], [
                        'id'                 => $task_id,
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
                $the_new_task->project->project_setting->increment(
                    ProjectSetting::$tasks_in[$the_new_task->task_status_id]
                );

                if (! empty($task->task_time_logs)) {
                    foreach ($task->task_time_logs as $task_time_log_id => $task_time_log) {
                        TimeLog::query()->updateOrCreate(['id' => $task_time_log_id], [
                            'id'         => $task_time_log_id,
                            'task_id'    => $task_id,
                            'project_id' => $task->project_id,
                            'user_id'    => $task_time_log->user_id,
                            'note'       => $task_time_log->note,
                            'from'       => str_replace('T00:00:00.000000Z', '', $task_time_log->from),
                            'to'         => str_replace('T00:00:00.000000Z', '', $task_time_log->to),
                            'time_spent' => $task_time_log->time_spent
                        ]);
                    }
                }

                if (! empty($task->task_comments)) {
                    foreach ($task->task_comments as $task_comment_id => $task_comment) {
                        TaskComment::query()->updateOrCreate(['id' => $task_comment_id], [
                            'id'         => $task_comment_id,
                            'user_id'    => $task_comment->user_id,
                            'task_id'    => $task_id,
                            'project_id' => $task->project_id,
                            'parent_id'  => $task_comment->parent_id,
                            'content'    => $task_comment->content,
                        ]);
                    }
                }

                if (! empty($task->task_checklists)) {
                    foreach ($task->task_checklists as $task_checklist_id => $task_checklist) {
                        TaskChecklist::query()->updateOrCreate(['id' => $task_checklist_id], [
                            'id'         => $task_checklist_id,
                            'task_id'    => $task_id,
                            'project_id' => $task->project_id,
                            'user_id'    => 1,
                            'name'       => $task_checklist->name,
                            'order'      => $task_checklist->order
                        ]);

                        if (! empty($task_checklist->task_checklist_items)) {
                            foreach ($task_checklist->task_checklist_items as $task_checklist_item_id
                                => $task_checklist_item
                            ) {
                                TaskChecklistItem::query()->updateOrCreate(['id' => $task_checklist_item_id], [
                                    'id'                => $task_checklist_item_id,
                                    'task_checklist_id' => $task_checklist_id,
                                    'task_id'           => $task_id,
                                    'project_id'        => $task->project_id,
                                    'user_id'           => 1,
                                    'name'              => $task_checklist_item->name,
                                    'order'             => $task_checklist_item->order,
                                    'is_checked'        => $task_checklist_item->is_checked
                                ]);
                            }
                        }
                    }
                }
                $the_new_task->project->project_setting->increment('tasks_total');
                $bar->advance();
            } $bar->finish();
        }); DB::commit();
    }
}
