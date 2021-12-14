<?php

namespace Database\Seeders;

use Parsedown;
use Illuminate\Database\Seeder;
use App\Models\Project\Project;
use Illuminate\Support\Facades\DB;
use App\Models\Project\ProjectSetting;
use Illuminate\Support\Facades\Storage;

class ProjectSeeder extends Seeder
{
    public $key = 0;

    /**
    * Run the database seeders.
    *
    * @return void
    * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
    */
    public function run()
    {
        if (! Storage::disk('local')->has('projects/projects.json')) return;
        $this->process(collect(json_decode(Storage::disk('local')->get('projects/projects.json'))));
    }

    /**
    * @param $projects
    */
    public function process($projects)
    {
        DB::transaction(function () use ($projects) {
            $bar = $this->command->getOutput()->createProgressBar(count($projects));
            $parsedown = (new Parsedown())->setSafeMode(true);
            foreach ($projects as $project_id => $project) {
                Project::updateOrCreate(['id' => $project->id], [
                    'id'              => $project->id,
                    'user_id'         => $project->user_id,
                    'name'            => $project->name,
                    'description'     => $parsedown->parse($project->description),
                    'code'            => $project->code,
                    'icon'            => $project->icon,
                    'color'           => $project->color
                ]);

                ProjectSetting::updateOrCreate(['project_id' => $project->id], [
                    'project_id'         => $project->id,
                    'view_id'            => 1,
                    'tasks_total'        => 0,
                    'tasks_in_todo'      => 0,
                    'tasks_in_progress'  => 0,
                    'tasks_in_completed' => 0,
                    'tasks_in_archived'  => 0
                ]); $bar->advance();
            } $bar->finish();
        }); DB::commit();
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