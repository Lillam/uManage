<?php

namespace Database\Seeders;

use App\Models\User\User;
use App\Models\Task\Task;
use Illuminate\Support\Str;
use App\Models\Project\Project;
use App\Models\Task\TaskStatus;
use Illuminate\Database\Seeder;
use App\Models\Task\TaskPriority;
use App\Models\Task\TaskIssueType;

class FakeTaskSeeder extends Seeder
{
    /**
    * Define the amount of entities that want to be made by this seeder
    *
    * @var int
    */
    public int $tasksToMake = 1000;

    /**
    * Run the database seeders.
    *
    * @return void
    */
    public function run(): void
    {
        $users          = User::all();
        $projects       = Project::all();
        $taskIssueTypes = TaskIssueType::all();
        $taskStatuses   = TaskStatus::all();
        $taskPriorities = TaskPriority::all();

        $bar = $this->command->getOutput()->createProgressBar($this->tasksToMake);

        for ($i = 0; $i < $this->tasksToMake; $i++) {
            $task = Task::query()->create([
                'project_id'         => $projects->random()->id,
                'name'               => Str::random(),
                'description'        => '',
                'task_issue_type_id' => $taskIssueTypes->random()->id,
                'reporter_user_id'   => $users->random()->id,
                'assigned_user_id'   => $users->random()->id,
                'task_status_id'     => $taskStatuses->random()->id,
                'task_priority_id'   => $taskPriorities->random()->id,
                'due_date'           => null
            ]);

            $task->project->projectSetting->increment('total_tasks');

            $bar->advance();
        }

        $bar->finish();
    }
}
