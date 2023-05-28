<?php

namespace App\Console\Commands\Project;

use App\Models\User\User;
use App\Models\Project\Project;
use Illuminate\Console\Command;
use App\Models\Project\ProjectUserContributor;

class AttachProjectUserContributorsCommand extends Command
{
    /**
     * @var string
     */
    protected $name = 'project.contributors.add';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:contributors:add {users}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add Users as Contributors to Projects';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $userIds = explode(',', $this->argument('users'));

        $projects = Project::all();
        $users = User::query()->whereIn('id', $userIds)->get();

        foreach ($projects as $project) {
            foreach ($users as $user) {
                $this->attach($project, $user);
            }
        }

        $this->info("finished attaching users");

        return 0;
    }

    private function attach(Project $project, User $user): void
    {
        ProjectUserContributor::updateOrCreate([
            'project_id' => $project->id,
            'user_id'    => $user->id
        ], [
            'project_id' => $project->id,
            'user_id'    => $user->id
        ]);

        $this->info("Attached {$user->getfullName()} to {$project->name}");
    }

    private function detatch(Project $project, User $user): void
    {
        ProjectUserContributor::query()
            ->where('project_id', '=', $project->id)
            ->where('user_id', '=', $user->id)
            ->delete();

        $this->info("Unattached {$user->getFullName()} from {$project->name}");
    }
}