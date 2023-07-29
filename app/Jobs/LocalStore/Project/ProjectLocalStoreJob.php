<?php

namespace App\Jobs\LocalStore\Project;

use App\Models\Project\Project;
use App\Jobs\LocalStore\LocalStoreJob;
use Illuminate\Database\Eloquent\Collection;

class ProjectLocalStoreJob extends LocalStoreJob
{
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

        $this->projects = Project::query()
            ->with([
                'userContributors'
            ])
            ->get();
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

            foreach ($project->userContributors as $contributor) {
                $this->put[$project->id]['userContributors'][] = $contributor->user_id;
            }
        }

        // after getting all the results, turn the entire collection into a json object and store it into a file. this
        // will be stored inside the local storage; public/storage/projects/projects.json?
        $this->putToStorage('local');
    }
}
