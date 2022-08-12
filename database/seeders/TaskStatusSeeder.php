<?php

namespace Database\Seeders;

use App\Models\Task\TaskStatus;
use Illuminate\Database\Seeder;

class TaskStatusSeeder extends Seeder
{
    use Incremental;

    /**
    * Run the database seeder.
    *
    * This method simply injects the core Task Statuses into the database which will be utilised by the project task
    * system as a way for filtering what the user needs to work on next and a way of organising their tasks.
    *
    * @return void
    */
    public function run(): void
    {
        $taskStatuses = [
            $this->increment() => (object) [
                'name'  => 'To Do',
                'type'  => TaskStatus::$TYPE_TODO,
                'color' => 'e1e1e1'
            ],
            $this->increment() => (object) [
                'name'  => 'In Progress',
                'type'  => TaskStatus::$TYPE_IN_PROGRESS,
                'color' => '2684ff'
            ],
            $this->increment() => (object) [
                'name'  => 'Done',
                'type'  => TaskStatus::$TYPE_DONE,
                'color' => '00875a'
            ],
            $this->increment() => (object) [
                'name'  => 'Archived',
                'type'  => TaskStatus::$TYPE_ARCHIVED,
                'color' => 'dd4b39'
            ]
        ];

        $bar = $this->command->getOutput()->createProgressBar(count($taskStatuses));

        foreach ($taskStatuses as $id => $taskStatus) {
            TaskStatus::query()->updateOrCreate(['id' => $id], [
                'id'    => $id,
                'name'  => $taskStatus->name,
                'type'  => $taskStatus->type,
                'color' => $taskStatus->color
            ]);

            $bar->advance();
        }

        $bar->finish();
    }
}
