<?php

namespace Database\Seeders;

use App\Models\User\User;
use App\Models\Project\Project;
use Illuminate\Database\Seeder;
use App\Models\Project\ProjectUserContributor;

class ProjectUserContributorSeeder extends Seeder
{
    /**
    * Run the database seeders.
    * This seeder is specifically targetted for inserting all users against all projects, that aren't their own. so
    * that I am able to test the connections between users and the people that they are working with.
    *
    * @return void
    */
    public function run()
    {
        // delete everything before re-applying everything into the database.
        ProjectUserContributor::whereNotNull('user_id')->delete();
        
        $projects = Project::all();
        $users     = User::all();
        foreach ($projects as $project_key => $project) {
            foreach ($users as $user_key => $user) {
                ProjectUserContributor::create([
                    'user_id' => $user->id,
                    'project_id' => $project->id
                ]);
            }
        }
    }
}
