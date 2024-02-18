<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task\TaskIssueType;

class TaskIssueTypeSeeder extends Seeder
{
    use Incremental;

    /**
    * Run the database seeders.
    *
    * @return void
    */
    public function run(): void
    {
        $taskIssueTypes = [
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

        foreach ($taskIssueTypes as $id => $taskIssueType) {
            TaskIssueType::query()->updateOrCreate(['id' => $id], [
                'id'    => $id,
                'name'  => $taskIssueType->name,
                'color' => $taskIssueType->color,
                'icon'  => $taskIssueType->icon
            ]);

            $bar->advance();
        }

        $bar->finish();
    }
}
