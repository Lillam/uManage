<?php

namespace App\Jobs\LocalStore\Project;

use Illuminate\Bus\Queueable;
use App\Models\Project\Project;
use App\Jobs\LocalStore\Puttable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use App\Jobs\LocalStore\Destinationable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;

class ProjectLocalStoreJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Destinationable, Puttable;

    /**
    * @var Project[]|Collection
    */
    private array|Collection $projects;

    /**
    * Create a new job instance.
    *
    * ProjectLocalStoreJob constructor.
    *
    * @return void
    */
    public function __construct(?string $destination = 'projects/projects.json')
    {
        $this->setDestination($destination);

        $this->projects = Project::all();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        foreach ($this->projects as $project) {
            $this->put[$project->id] = [
                'id'          => $project->id,
                'user_id'     => $project->user_id,
                'name'        => $project->name,
                'code'        => $project->code,
                'description' => $project->description,
                'icon'        => $project->icon,
                'color'       => $project->color
            ];
        }

        // after getting all the results, turn the entire collection into a json object and store it into a file. this
        // will be stored inside the local storage; public/storage/projects/projects.json?
        Storage::disk('local')->put($this->getDestination(), json_encode($this->put));
    }
}
