<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task\TaskPriority;

class TaskPrioritySeeder extends Seeder
{
    use Incremental;

    /**
    * Run the database seeders.
    *
    * This method seeds the default priorities of the project task system, as a way for the user to be able to organise
    * their tasks into priorities as to what might need working on first. These are the default values a user will have
    *
    * @return void
    */
    public function run(): void
    {
        $taskPriorities = [
            $this->increment() => (object) [
                'name'  => 'Highest',
                'color' => 'bf0000',
                'icon'  => 'fa fa-angle-double-up'
            ],
            $this->increment() => (object) [
                'name'  => 'High',
                'color' => 'ff0000',
                'icon'  => 'fa fa-angle-up'
            ],
            $this->increment() => (object) [
                'name'  => 'Medium',
                'color' => 'ffa500',
                'icon'  => 'fa fa-angle-right',
            ],
            $this->increment() => (object) [
                'name'  => 'Low',
                'color' => '228C22',
                'icon'  => 'fa fa-angle-down'
            ],
            $this->increment() => (object) [
                'name'  => 'Lowest',
                'color' => '09bb49',
                'icon'  => 'fa fa-angle-double-down'
            ],
        ];

        $bar = $this->command->getOutput()->createProgressBar(count($taskPriorities));

        foreach ($taskPriorities as $id => $taskPriority) {
            TaskPriority::query()->updateOrCreate(['id' => $id], [
                'id' => $id,
                'name' => $taskPriority->name,
                'color' => $taskPriority->color,
                'icon' => $taskPriority->icon
            ]);

            $bar->advance();
        }

        $bar->finish();
    }
}
