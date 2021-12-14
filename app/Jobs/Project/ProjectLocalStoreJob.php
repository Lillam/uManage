<?php

namespace App\Jobs\Project;

use Illuminate\Bus\Queueable;
use App\Models\Project\Project;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use \Illuminate\Database\Eloquent\Collection;

class ProjectLocalStoreJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
    * @var Project[]|Collection
    */
    private $projects;

    /**
    * @var array
    */
    private array $put_projects;

    /**
    * Create a new job instance.
    *
    * ProjectLocalStoreJob constructor.
    *
    * @return void
    */
    public function __construct()
    {
        $this->projects = Project::all();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->projects as $project) {
            $this->put_projects[$project->id] = [
                'id'              => $project->id,
                'user_id'         => $project->user_id,
                'name'            => $project->name,
                'code'            => $project->code,
                'description'     => $project->description,
                'icon'            => $project->icon,
                'color'           => $project->color
            ];
        }

        // after getting all of the results, turn the entire collection into a json object and store it into a file.
        // this will be stored inside the local storage; public/storage/projects/projects.json?
        Storage::disk('local')->put(
            'projects/projects.json',
            json_encode($this->put_projects)
        );
    }
}
