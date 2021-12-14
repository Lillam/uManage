<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task\TaskIssueType;

class TaskIssueTypeSeeder extends Seeder
{
    public $key = 0;

    /**
    * Run the database seeders.
    *
    * @return void
    */
    public function run()
    {
        $task_issue_types = [
            $this->increment() => (object) [
                'name' => 'New Feature',
                'color' => '00875a',
                'icon' => 'fa fa-plus'
            ],
            $this->increment() => (object) [
                'name' => 'Bug',
                'color' => 'dd4b39',
                'icon' => 'fa fa-exclamation-circle',
            ],
            $this->increment() => (object) [
                'name' => 'Research',
                'color' => '1e87f0',
                'icon' => 'fa fa-magnifying-glass'
            ]
        ];

        $bar = $this->command->getOutput()->createProgressBar($this->key);

        foreach ($task_issue_types as $id => $task_issue_type) {
            TaskIssueType::updateOrCreate(['id' => $id], [
                'id' => $id,
                'name' => $task_issue_type->name,
                'color' => $task_issue_type->color,
                'icon' => $task_issue_type->icon
            ]);

            $bar->advance();
        }

        $bar->finish();
    }

    /**
    * @return int
    */
    public function increment(): int
    {
        $this->key += 1;
        return $this->key;
    }
}
