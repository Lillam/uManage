<?php

namespace Database\Seeders;

use App\Models\Task\Task;
use App\Models\Timelog\Timelog;
use Illuminate\Database\Seeder;
use App\Models\Task\TaskComment;
use Illuminate\Support\Facades\DB;
use App\Models\Task\TaskChecklist;
use App\Models\Project\ProjectSetting;
use App\Models\Task\TaskChecklistItem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class TaskSeeder extends Seeder
{
    /**
    * Run the database seeders.
    *
    * @return void
    * @throws FileNotFoundException
    */
    public function run()
    {
        if (! Storage::disk('local')->has('tasks/tasks.json'))
            return;

        $this->process(collect(
            json_decode(Storage::disk('local')->get('tasks/extract_timelogs.json'))
        ));
    }

    /**
    * @param $tasks
    * @return void
    */
    public function process($tasks)
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

            $the_task_id = 0;
            $timelog_id = 0;

            foreach ($tasks as $task_id => $task) {
                // remove the auto incremenetal factor for now.
                $the_task_id += 1;
                try {
                    $the_new_task = Task::updateOrCreate(['id' => $the_task_id], [
                        'id'                 => $the_task_id,
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
                } catch (\Exception $exception) {
                    continue;
                }

                // finding which particular setting we're going to be incrementing, by acquiring it from the project
                // setting model, which will remove the need for having to do if this, if that, if this other thing and
                // doing this in a one liner.
                $the_new_task->project->project_setting->increment(
                    ProjectSetting::$tasks_in[$the_new_task->task_status_id]
                );

                if (! empty($task->task_timelogs)) {
                    foreach ($task->task_timelogs as $task_timelog) {
                        $timelog_id += 1;
                        Timelog::updateOrCreate(['id' => $timelog_id], [
                            'id'         => $timelog_id,
                            'task_id'    => $the_task_id,
                            'project_id' => $task->project_id,
                            'user_id'    => $task_timelog->user_id,
                            'note'       => $task_timelog->note,
                            'from'       => str_replace('T00:00:00.000000Z', '', $task_timelog->from),
                            'to'         => str_replace('T00:00:00.000000Z', '', $task_timelog->to),
                            'time_spent' => $task_timelog->time_spent
                        ]);
                    }
                }

                if (! empty($task->task_comments)) {
                    foreach ($task->task_comments as $task_comment_id => $task_comment) {
                        TaskComment::updateOrCreate(['id' => $task_comment_id], [
                            'id'         => $task_comment_id,
                            'user_id'    => $task_comment->user_id,
                            'task_id'    => $the_task_id,
                            'project_id' => $task->project_id,
                            'parent_id'  => $task_comment->parent_id,
                            'content'    => $task_comment->content,
                        ]);
                    }
                }

                if (! empty($task->task_checklists)) {
                    foreach ($task->task_checklists as $task_checklist_id => $task_checklist) {
                        TaskChecklist::updateOrCreate(['id' => $task_checklist_id], [
                            'id'         => $task_checklist_id,
                            'task_id'    => $the_task_id,
                            'project_id' => $task->project_id,
                            'user_id'    => 1,
                            'name'       => $task_checklist->name,
                            'order'      => $task_checklist->order
                        ]);

                        if (! empty($task_checklist->task_checklist_items)) {
                            foreach ($task_checklist->task_checklist_items as $task_checklist_item_id
                                => $task_checklist_item
                            ) {
                                TaskChecklistItem::updateOrCreate(['id' => $task_checklist_item_id], [
                                    'id'                => $task_checklist_item_id,
                                    'task_checklist_id' => $task_checklist_id,
                                    'task_id'           => $the_task_id,
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