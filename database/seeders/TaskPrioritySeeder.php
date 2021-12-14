<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task\TaskPriority;

class TaskPrioritySeeder extends Seeder
{
    public $key = 0;

    /**
    * Run the database seeders.
    *
    * @return void
    */
    public function run()
    {
        $task_priorities = [
            $this->increment() => (object) [
                'name' => 'Highest',
                'color' => 'bf0000',
                'icon' => 'fa fa-angle-double-up'
            ],
            $this->increment() => (object) [
                'name' => 'High',
                'color' => 'ff0000',
                'icon' => 'fa fa-angle-up'
            ],
            $this->increment() => (object) [
                'name' => 'Medium',
                'color' => 'ffa500',
                'icon' => 'fa fa-angle-right',
            ],
            $this->increment() => (object) [
                'name' => 'Low',
                'color' => '228C22',
                'icon' => 'fa fa-angle-down'
            ],
            $this->increment() => (object) [
                'name' => 'Lowest',
                'color' => '09bb49',
                'icon' => 'fa fa-angle-double-down'
            ],
        ];

        $bar = $this->command->getOutput()->createProgressBar(count($task_priorities));

        foreach ($task_priorities as $id => $task_priority) {
            TaskPriority::updateOrCreate(['id' => $id], [
                'id' => $id,
                'name' => $task_priority->name,
                'color' => $task_priority->color,
                'icon' => $task_priority->icon
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
