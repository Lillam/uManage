<?php

namespace Database\Seeders;

use App\Models\Task\TaskStatus;
use Illuminate\Database\Seeder;

class TaskStatusSeeder extends Seeder
{
    public $key = 0;

    /**
    * Run the database seeders.
    *
    * @return void
    */
    public function run()
    {
        $task_statuses = [
            $this->increment() => (object) [ 'name' => 'To Do',       'type' => TaskStatus::$TYPE_TODO,        'color' => 'e1e1e1' ],
            $this->increment() => (object) [ 'name' => 'In Progress', 'type' => TaskStatus::$TYPE_IN_PROGRESS, 'color' => '2684ff' ],
            $this->increment() => (object) [ 'name' => 'Done',        'type' => TaskStatus::$TYPE_DONE,        'color' => '00875a' ],
            $this->increment() => (object) [ 'name' => 'Archived',    'type' => TaskStatus::$TYPE_ARCHIVED,    'color' => 'dd4b39' ]
        ];

        $bar = $this->command->getOutput()->createProgressBar(count($task_statuses));

        foreach ($task_statuses as $id => $task_status) {
            TaskStatus::updateOrCreate(['id' => $id], [
                'id' => $id,
                'name' => $task_status->name,
                'type' => $task_status->type,
                'color' => $task_status->color
            ]);

            $bar->advance();
        }

        $bar->finish();
    }

    public function increment()
    {
        $this->key += 1;
        return $this->key;
    }
}
